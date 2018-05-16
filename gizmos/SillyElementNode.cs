using System;
using System.Collections.Generic;

namespace SillyWidgets
{
    public class SillyElementNode : SillyHtmlNode
    {
        private List<SillyHtmlNode> Children = new List<SillyHtmlNode>();

        public Dictionary<string, string> Attributes { get; private set; }
        public virtual SillyAttribute SillyAttr { get; protected set; }
        public bool IsSilly 
        { 
            get
            {
                return(SillyAttr != null);
            }
        }

        public SillyElementNode(string name)
            : base(name)
        {            
            Attributes = new Dictionary<string, string>();
            SillyAttr = null;
        }

        public override void Accept(ISillyNodeVisitor visitor)
        {
            visitor.VisitElement(this);
        }

        public virtual void SetSilly(SillyAttribute data)
        {
            SillyAttr = data;
        }

        public virtual void Attach(ISillyWidget widget)
        {
            if (SillyAttr == null)
            {
                throw new SillyException(SillyHttpStatusCode.ServerError, "Trying to bind to non-widget node: " + this.Name);
            }
                
            if (widget == null)
            {
                throw new SillyException(SillyHttpStatusCode.ServerError, "Can't bind a null widget to node: " + this.Name);
            }

            if (SillyAttr.SetWidget(widget))
            {
                widget.Resolve(this);
            }
            else
            {
                throw new SillyException(SillyHttpStatusCode.ServerError, "Cannot bind widget " + widget.Flavor.ToString() + " to node " + SillyAttr.ToString());
            }
        }

        public void AddChild(SillyHtmlNode node)
        {
            node.Parent = this;
            Children.Add(node);
        }

        public override List<SillyHtmlNode> GetChildren()
        {
            return(Children);
        }

        public void DeleteChildren()
        {
            Children.Clear();
        }

        public virtual void SetAttribute(string name, string value)
        {
            Attributes[name.ToLower()] = value;
        }

        public virtual string GetAttribute(string name)
        {
            string val = string.Empty;

            Attributes.TryGetValue(name.ToLower(), out val);

            return(val);
        }

        public override void Print(string indent, bool last)
        {
            Console.Write(indent);

            if (last)
            {
                Console.Write("\\-");
                indent += "  ";
            }
            else
            {
                Console.Write("|-");
                indent += "| ";
            }

            Console.WriteLine(base.Name);

            for (int i = 0; i < Children.Count; ++i)
            {
                Children[i].Print(indent, i == Children.Count - 1);
            }
        }
    }
}