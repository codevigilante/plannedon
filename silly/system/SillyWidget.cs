using System;
using System.Collections.Generic;
using System.Xml.Linq;
using System.Xml;
using System.IO;
using System.Threading.Tasks;
using System.Text;

namespace SillyWidgets
{
    public enum BindResult { Success, NotFound, InvalidWidget, NoDocument, AlreadyExists }

    public class SillyWidget
    {
        private static string AttributeName = "silly-key";
        private static string ElementAttributeName = "key";

        public string Key { get; private set; }
        public XDocument Document { get; protected set; }
        public enum SillyType { Text, List, Widget, Page, What }
        [Flags]
        public enum SillyTargets { Element, Attribute, Unknown }
        public SillyType Type { get; private set; }

        public string ElementName { get; protected set; }
        public SillyTargets SupportedTargets { get; protected set; }
        public virtual string Html
        { 
            get
            {
                return(Render());
            }
            set {} 
        }

        // WidgetElementName -> { key, Widget }, { key, Widget }, ...
        // "silly-key" -> { key, Widget }, ...
        private Dictionary<string, SillyBindMap> BoundWidgets = new Dictionary<string, SillyBindMap>();
        private StringBuilder Content = new StringBuilder();
        private Stack<XNode> Nodes = new Stack<XNode>();

        public SillyWidget(string id, SillyType type = SillyType.Widget)
        {
            Key = id;
            Type = type;
            ElementName = "SillyWidget";
            SupportedTargets = SillyTargets.Attribute | SillyTargets.Element;
        }

        public BindResult Bind(SillyWidget widget)
        {     
            if (widget == null)
            {
                return (BindResult.InvalidWidget);
            }

            if (Document == null)
            {
                return (BindResult.NoDocument);
            }

            if ((widget.SupportedTargets & SillyTargets.Element) == SillyTargets.Element)
            {
                AddToBound(widget.ElementName, widget);
            }

            if ((widget.SupportedTargets & SillyTargets.Attribute) == SillyTargets.Attribute)
            {
                AddToBound(AttributeName, widget);
            }

            return(BindResult.Success);
        }

        private void AddToBound(string outerKey, SillyWidget widget)
        {
            SillyBindMap bindVals = null;

            if (!BoundWidgets.TryGetValue(outerKey, out bindVals))
            {
                bindVals = new SillyBindMap();
                BoundWidgets.Add(outerKey, bindVals);
            }

            bindVals[widget.Key] = widget;
        }

        protected virtual bool Resolve(XElement element, SillyTargets target)
        {
            if (element == null)
            {
                return(false);
            }

            if (Document == null ||
                Document.Root.Attribute("id") != element.Attribute("id"))
            {
                // Document = SillyResourceBucket.FetchWidget(element.Attribute("id"));
            }
            // else if (Document.Root.Attribute("id") != element.Attribute("id"))
            //{
                // Document 

            //}

            // do whatever a widget needs to do to resolve

            return(true);
        }

        protected virtual string Render()
        {
            if (Document == null || Document.Root == null)
            {
                return(string.Empty);
            }

            Content.Clear();
            Nodes.Clear();
            Nodes.Push(Document.Root);

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

            SillyWidget widget = null;
            SillyTargets target;

            if (TryMatchWidget(element, out widget, out target))
            {
                bool renderWidget = widget.Resolve(element, target);

                if (renderWidget)
                {
                    switch(target)
                    {
                        case SillyTargets.Element:
                            Content.Append(widget.Html);
                            break;
                        case SillyTargets.Attribute:
                            // render element
                            Content.Append(widget.Html);
                            // render close tag
                            break;
                        default:
                            Content.Append("This is an error, somehow, not sure how it can happen.");
                            break;
                    }
                    Content.Append(widget.Html);
                }

                return;
            }

            if (!String.IsNullOrEmpty(element.Attribute("silly-exit")?.Value))
            {
                element.SetAttributeValue("silly-exit", null);

                Content.Append("</");
                Content.Append(element.Name);
                Content.Append(">");
            }
            else
            {
                string lowerName = element.Name.ToString().ToLower();

                Content.Append("<");
                Content.Append(lowerName);
                
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

                if (element.LastNode != null)
                {
                    Content.Append(">");
                    element.SetAttributeValue("silly-exit", 1);
                    Nodes.Push(element);

                    XNode child = element.LastNode;

                    while (child != null)
                    {
                        Nodes.Push(child);
                        child = child.PreviousNode;
                    }
                }
                else
                {
                    switch(lowerName)
                    {
                        case "meta":
                        case "link":
                        case "span":
                            Content.Append(">");
                            break;
                        case "script":
                            Content.Append("></script>");
                            break;
                        default:
                            Content.Append(" />");
                            break;
                    }
                }
            }
        } 

        private void RenderText(XText text)
        {
            if (text == null)
            {
                return;
            }

            Content.Append(text.Value);
        }

        private bool TryMatchWidget(XElement element, out SillyWidget widget, out SillyTargets target)
        {
            widget = null;
            target = SillyTargets.Unknown;

            SillyBindMap matchingWidgets = null;
            string widgetKey = string.Empty;

            if (BoundWidgets.TryGetValue(element.Name.ToString(), out matchingWidgets))
            {                
                widgetKey = element.Attribute(ElementAttributeName).Value;
                target = SillyTargets.Element;
            }
            else if (BoundWidgets.TryGetValue(AttributeName, out matchingWidgets))
            {
                widgetKey = element.Attribute(AttributeName).Value;
                target = SillyTargets.Attribute;
            }
            else
            {
                return(false);
            }

            if (String.IsNullOrEmpty(widgetKey))
            {
                return(false);
            }

            return(matchingWidgets.TryGetValue(widgetKey, out widget));
        }
    }
}