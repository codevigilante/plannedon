using System;
using System.Collections.Generic;
using System.Xml.Linq;
using System.Xml;
using System.IO;
using System.Threading.Tasks;
using System.Text;

namespace SillyWidgets
{
    public enum BindResult { Success, InvalidWidget }
    public enum SillyOptions { SilenceErrors, ShowErrors }

    public class SillyWidget
    {
        private static string AttributeKey = "silly-key";
        private static string ExitingAttribute = "silly-exit";
        private static string ElementKey = "key";

        public string Key { get; private set; }
        public enum SillyType { Text, List, ListItem, Widget, Page, What }
        [Flags]
        public enum SillyTargets { Element = 1, Attribute = 2, Unknown = 4, None = 8 }
        public SillyType Type { get; private set; }
        public SillyTargets SupportedTargets { get; protected set; }
        public virtual string Html
        { 
            get
            {
                if (Document == null)
                {
                    return(string.Empty);
                }

                string html = Render(Document.Root, IsSilly(Document.Root.Name.ToString()));

                return(GetFormattedErrors() + html);
            }
            set {} 
        }
        public List<string> ErrorCollection { get; private set; }
        public SillyOptions Options { get; set; }

        protected XDocument Document { get; set; }
        protected string ElementName { get; set; }
        protected StringBuilder Content { get; private set; }

        private SillyBindMap BoundWidgets = new SillyBindMap();
        private StringBuilder Errors = new StringBuilder();
        private string ErrorTemplate = "<li style=\"color:red;border:1px solid red;padding:5px;\"><b>ERROR</b>: {0}</li>";
        private Stack<XNode> Nodes = new Stack<XNode>();

        public SillyWidget(string id, SillyType type = SillyType.Widget)
        {
            Key = id;
            Type = type;
            SupportedTargets = SillyTargets.Attribute | SillyTargets.Element;
            ErrorCollection = new List<string>();
            Options = SillyOptions.ShowErrors;
            ElementName = "silly";
            Content = new StringBuilder();
        }

        public virtual BindResult Bind(SillyWidget widget)
        {     
            if (widget == null || String.IsNullOrEmpty(widget.Key))
            {
                return (BindResult.InvalidWidget);
            }

            BoundWidgets[widget.Key] = widget;

            return(BindResult.Success);
        }

        public string GetFormattedErrors()
        {
            if (ErrorCollection.Count == 0 || Options == SillyOptions.SilenceErrors)
            {
                return(string.Empty);
            }

            Errors.Clear();
            Errors.Append("<ul>");

            foreach(string err in ErrorCollection)
            {
                Errors.Append(String.Format(ErrorTemplate, err));
            }

            Errors.Append("</ul>");

            return(Errors.ToString());
        }

        protected virtual bool Accept(XElement element)
        {
            if (element == null)
            {
                return(false);
            }

            if (Document != null)
            {
                return(true);
            }

            string widget = element.Attribute("widget")?.Value;

            if (String.IsNullOrEmpty(widget))
            {
                ErrorCollection.Add("Widget '" + Key + "' must declare a 'widget' attribute naming a valid widget.");

                return(false);
            }

            SillyResourceInfo info = null;

            if (SillyResourceBucket.Fetch(widget, out info))
            {
                Document = info.ShtmlDoc;
            }
            else
            {
                ErrorCollection.Add("No widget found matching '" + widget + "' at key '" + Key + "'");
            }

            return(true);
        }

        protected virtual string Render(XElement root, bool skipRoot = false)
        {
            if (root == null)
            {
                return(string.Empty);
            }

            Content.Clear();
            ErrorCollection.Clear();
            Nodes.Clear();

            if (skipRoot)
            {
                PushElementChildren(root);
            }
            else
            {
                Nodes.Push(root);
            }

            while(Nodes.Count > 0)
            {
                XNode node = Nodes.Pop();

                switch(node.NodeType)
                {
                    case XmlNodeType.Element:
                        RenderElement(node as XElement);
                        break;
                    case XmlNodeType.Text:
                        RenderText(node as XText);
                        break;
                    case XmlNodeType.Comment:
                    default:
                        break;
                }
            }

            return(Content.ToString());
        }

        private void RenderElement(XElement element)
        {
            if (element == null)
            {
                return;
            }

            if (!String.IsNullOrEmpty(element.Attribute(ExitingAttribute)?.Value))
            {
                element.SetAttributeValue(ExitingAttribute, null);

                CloseElement(element);
            }
            else            
            {
                XText widgetContent = null;
                bool renderElement = true;

                if (TryGetWidget(element, out widgetContent, out renderElement))
                {                    
                    if (renderElement)
                    {
                        OpenElement(element);
                        element.SetAttributeValue("silly-exit", 1);
                        Nodes.Push(element);
                    }

                    Nodes.Push(widgetContent);

                    return;
                }

                if (element.LastNode != null)
                {
                    OpenElement(element);

                    element.SetAttributeValue("silly-exit", 1);
                    Nodes.Push(element);

                    PushElementChildren(element);
                }
                else
                {
                    switch(element.Name.ToString().ToLower())
                    {
                        case "meta":
                        case "link":
                        case "span":
                            OpenElement(element);
                            break;
                        case "script":
                            OpenElement(element);
                            Content.Append("</script>");
                            break;
                        default:
                            OpenElement(element, true);
                            break;
                    }
                }
            }
        } 

        private void OpenElement(XElement element, bool selfClose = false)
        {
            Content.Append("<");
            Content.Append(element.Name);
                
            if (element.HasAttributes)
            {
                Content.Append(" ");

                foreach(XAttribute attr in element.Attributes())
                {
                    Content.Append(attr.ToString());

                    if (attr != element.LastAttribute)
                    {
                        Content.Append(" ");
                    }
                }
            }

            if (selfClose)
            {
                Content.Append(" />");
            }
            else
            {
                Content.Append(">");
            }
        }

        private void CloseElement(XElement element)
        {
            Content.Append("</");
            Content.Append(element.Name);
            Content.Append(">");
        }

        private void RenderText(XText text)
        {
            if (text == null)
            {
                return;
            }

            Content.Append(text.Value);
        }

        private bool TryGetWidget(XElement element, out XText widgetContent, out bool renderElement)
        {
            widgetContent = null;
            renderElement = true;

            bool isElementWidget = false;
            string keyName = string.Empty;
            string keyValue = null;

            if (IsSilly(element.Name.ToString()))
            {
                isElementWidget = true;
                keyName = ElementKey;
            }
            else
            {
                keyName = AttributeKey;
            }

            keyValue = element.Attribute(keyName)?.Value;

            if (keyValue == null)
            {
                return (false);
            }

            SillyWidget widget = null;

            if (BoundWidgets.TryGetValue(keyValue, out widget))
            {
                bool accepted = widget.Accept(element);

                if (!accepted)
                {
                    ErrorCollection.Add("Cannot bind " + widget.GetType().Name + " to key '" + keyValue + "'");

                    return(false);
                }

                widgetContent = new XText(widget.Html);
                renderElement = !isElementWidget;
                ErrorCollection.AddRange(widget.ErrorCollection);

                return(true);
            }

            return(false);
        }

        private bool IsSilly(string elementName)
        {
            return(String.Compare(elementName, ElementName, false) == 0);
        }

        private void PushElementChildren(XElement element)
        {
            XNode child = element.LastNode;

            while (child != null)
            {
                Nodes.Push(child);
                child = child.PreviousNode;
            }
        }
    }
}