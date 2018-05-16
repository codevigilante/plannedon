using System;
using System.Collections.Generic;
using System.Text;

namespace SillyWidgets
{
    public abstract class SillyHtmlNode
    {
        public SillyHtmlNode Parent { get; set; }
        public string Name { get; set; }
        public bool HasCloseTag { get; set; }
        public bool SelfCloseTag { get; set; }
        public bool Visited { get; set; }

        public SillyHtmlNode(string name)
        {
            Name = name;
            Parent = null;
            Visited = false;
            HasCloseTag = SelfCloseTag = false;
        }

        public abstract List<SillyHtmlNode> GetChildren();
        public abstract void Accept(ISillyNodeVisitor visitor);
        public abstract void Print(string indent, bool last);
    }
}