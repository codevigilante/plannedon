using System;
using System.Xml.Linq;

namespace SillyWidgets
{
    public class SillyTextWidget : SillyWidget
    {
        public string Text { get; private set; }

        public SillyTextWidget(string id, string text)
            : base(id, SillyType.Text)
        {
            Text = text;
        }

        protected override bool Resolve(XElement node, SillyTargets target)
        {
            Console.WriteLine("Text widget");

            return(false);
        }
    }
}