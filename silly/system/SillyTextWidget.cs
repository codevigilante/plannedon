using System;
using System.Xml.Linq;

namespace SillyWidgets
{
    public class SillyTextWidget : SillyWidget
    {
        public override string Html
        {
            get
            {
                return(Text);
            }
        }
        public string Text { get; private set; }

        public SillyTextWidget(string id, string text)
            : base(id, SillyType.Text)
        {
            if (text == null)
            {
                Text = string.Empty;
            }
            else
            {
                Text = text;
            }
        }

        protected override bool Accept(XElement node)
        {
            if (node == null)
            {
                return(false);
            }

            return(true);
        }

        protected override string Render(XElement root, bool skipRoot = false)
        {
            return(Text);
        }
    }
}