using System;
using System.Text;
using System.Collections.Generic;
using System.Xml.Linq;

namespace SillyWidgets
{
    public class SillyHtmlRenderer : ISillyXmlVisitor
    {
        public List<string> Errors { get; private set; }
        
        private StringBuilder Result = new StringBuilder();
        private Stack<XNode> Nodes = new Stack<XNode>();
        

        public SillyHtmlRenderer()
        {
            Errors = new List<string>();
            Type type = typeof(XElement);
        }

        public bool Launch(XNode root, out string html)
        {
            html = string.Empty;
            Errors.Clear();
            Result.Clear();
            Nodes.Clear();

            if (root == null)
            {
                Errors.Add("Root node is null");

                return(false);
            }

            Nodes.Push(root);

            while(Nodes.Count > 0)
            {
                XNode node = Nodes.Pop();
                XElement element = node as XElement;

                if (element != null)
                {
                    Visit(element);

                    continue;
                }

                XText text = node as XText;

                if (text != null)
                {
                    Visit(text);

                    continue;
                }
            }

            html = Result.ToString();

            return(true);
        }

        public void Visit(XElement element)
        {
            if (!String.IsNullOrEmpty(element.Attribute("silly-exit")?.Value))
            {
                element.SetAttributeValue("silly-exit", null);

                Result.Append("</");
                Result.Append(element.Name);
                Result.Append(">");
            }
            else
            {
                string lowerName = element.Name.ToString().ToLower();

                Result.Append("<");
                Result.Append(lowerName);
                
                if (element.HasAttributes)
                {
                    Result.Append(" ");

                    foreach(XAttribute attr in element.Attributes())
                    {
                        Result.Append(attr.ToString());

                        if (attr != element.LastAttribute)
                        {
                            Result.Append(" ");
                        }
                    }
                }

                if (element.LastNode != null)
                {
                    Result.Append(">");
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
                            Result.Append(">");
                            break;
                        case "script":
                            Result.Append("></script>");
                            break;
                        default:
                            Result.Append(" />");
                            break;
                    }
                }
            }
        }

        public void Visit(XComment comment)
        {
            // no need to render comments
        }

        public void Visit(XText text)
        {
            Result.Append(text.Value);
        }

        public void Visit(XNode node)
        {
            Console.WriteLine("Node: ");
        }
    }
}