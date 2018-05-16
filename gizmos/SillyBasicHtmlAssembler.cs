using System;
using System.Text;
using System.Collections.Generic;

namespace SillyWidgets
{
    public class SillyBasicHtmlAssembler : ISillyNodeVisitor
    {
        private StringBuilder Payload = new StringBuilder();
        private Stack<SillyHtmlNode> NodeStack = new Stack<SillyHtmlNode>();

        public string Get()
        {
            return(Payload.ToString());
        }

        public void Reset()
        {
            Payload.Clear();
            NodeStack.Clear();
        }

        public void Go(SillyHtmlNode node)
        {
            if (node == null)
            {
                return;
            }

            NodeStack.Push(node);

            while(NodeStack.Count > 0)
            {
                SillyHtmlNode current = NodeStack.Pop();

                current.Accept(this);

                if (current.Visited)
                {
                    NodeStack.Push(current);
                
                    List<SillyHtmlNode> children = current.GetChildren();

                    for(int i = children.Count - 1; i >= 0; --i)
                    {
                        NodeStack.Push(children[i]);
                    }
                }
            }
        }

        public void VisitElement(SillyElementNode node)
        {
            if(node.Visited)
            {
                if (node.HasCloseTag)
                {
                    Payload.Append("</");
                    Payload.Append(node.Name);
                    Payload.Append(">");
                }

                node.Visited = false;

                return;
            }

            Payload.Append("<");
            Payload.Append(node.Name);

            if (node.Attributes.Count > 0)
            {
                foreach(KeyValuePair<string, string> attr in node.Attributes)
                {
                    Payload.Append(" ");
                    Payload.Append(attr.Key);
                    
                    if (!String.IsNullOrEmpty(attr.Value))
                    {
                        Payload.Append("=\"");
                        Payload.Append(attr.Value);
                        Payload.Append("\"");
                    }
                }
            }

            if (node.SelfCloseTag)
            {
                Payload.Append(" ");
                Payload.Append("/>");
            }
            else
            {
                Payload.Append(">");
            }

            node.Visited = true;
        }

        public void VisitWidget(SillyWidgetNode node)
        {
            if (node.SillyAttr.HasWidget)
            {
                node.Visited = !node.Visited;
                
                return;
            }

            VisitElement(node);   
        }

        public void VisitText(SillyTextNode node)
        {
            if(node.Visited)
            {
                node.Visited = false;

                return;
            }

            Payload.Append(node.Text);
            node.Visited = true;
        }
    }
}