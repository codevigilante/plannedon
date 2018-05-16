using System;
using System.Collections.Generic;

namespace SillyWidgets
{
    public class SillyTextNode : SillyHtmlNode
    {
        public string Text { get; set; }

        public SillyTextNode(string text)
            : base("text")
        {            
            Text = text;
        }

        public override List<SillyHtmlNode> GetChildren()
        {
            return (new List<SillyHtmlNode>());
        }

        public override void Accept(ISillyNodeVisitor visitor)
        {
            visitor.VisitText(this);
        }

        public override void Print(string indent, bool last)
        {
            Console.WriteLine(indent + "|-" + base.Name + ":" + Text);
        }
    }
}