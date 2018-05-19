using System;
using System.Text;
using System.Xml.Linq;
using System.Collections.Generic;

namespace SillyWidgets
{
    public class SillyListItem : SillyWidget
    {
        public SillyListItem()
            : base(string.Empty, SillyType.ListItem)
        {
            SupportedTargets = SillyTargets.None;
        }

        protected override bool Resolve(XElement element, SillyTargets target)
        {
            return(false);
        }
    }

    public class SillyListWidget : SillyWidget
    {
        public List<SillyListItem> Items { get; private set; }
        public override string Html
        {
            get
            {
                return(Render(Root));
            }
        }

        private XElement Root = null;
        private SillyTargets Target = SillyTargets.Unknown;

        public SillyListWidget(string id)
            : base(id, SillyType.List)
        {
            Items = new List<SillyListItem>();
            ElementName = "SillyList";
        }

        public void AddItem(SillyListItem item)
        {
            Items.Add(item);
        }

        protected override string Render(XElement root)
        {
            if (Target == SillyTargets.Attribute)
            {
                // render the element
                // render the list items
                // render close element
            }
            return(string.Empty);
        }

        protected override bool Resolve(XElement node, SillyTargets target)
        {
            if (node == null)
            {
                return(false);
            }

            Root = node;
            Target = target;
            
            return(true);
        }
    }
}