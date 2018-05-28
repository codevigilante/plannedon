using System;
using System.Text;
using System.Xml.Linq;
using System.Xml;
using System.Collections.Generic;

namespace SillyWidgets
{
    public class SillyListItem : SillyWidget
    {
        public override string Html
        {
            get
            {
                if (Target == null)
                {
                    return(string.Empty);
                }

                return(base.Render(Target, true));
            }
        }

        private XElement Target = null;

        public SillyListItem()
            : base(string.Empty, SillyType.ListItem)
        {
            SupportedTargets = SillyTargets.None;
        }

        protected override bool Accept(XElement element)
        {
            if (element == null)
            {
                return(false);
            }

            Target = element;
            
            return(true);
        }

        public bool AcceptPublic(XElement element)
        {
            return(Accept(element));
        }
    }

    public class SillyListWidget : SillyWidget
    {
        public List<SillyListItem> Items { get; private set; }
        public override string Html
        {
            get
            {
                if (Target == null)
                {
                    return(string.Empty);
                }

                Content.Clear();
                ErrorCollection.Clear();

                foreach(SillyListItem item in Items)
                {
                    bool accepted = item.AcceptPublic(Target);

                    if (!accepted)
                    {
                        ErrorCollection.Add("Cannot bind list item in silly list '" + Key + "'");

                        continue;
                    }

                    Content.Append(item.Html);
                    ErrorCollection.AddRange(item.ErrorCollection);
                }

                return(Content.ToString());
            }
        }

        private XElement Target = null;

        public SillyListWidget(string id)
            : base(id, SillyType.List)
        {
            Items = new List<SillyListItem>();
        }

        public void AddItem(SillyListItem item)
        {
            Items.Add(item);
        }

        protected override bool Accept(XElement node)
        {
            if (node == null)
            {
                return(false);
            }

            Target = node;
            
            return(true);
        }
    }
}