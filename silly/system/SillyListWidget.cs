using System;
using System.Text;
using System.Xml.Linq;
using System.Collections.Generic;

namespace SillyWidgets
{
    public class SillyListItem : Dictionary<string, SillyWidget>
    {
        public SillyListItem(string id)
        {
            /*Flavor = new SillyFlavor()
            {
                Type = SillyFlavor.SillyType.ListItem,
                Namespace = SillyFlavor.SillyNamespace.silly,
                Key = id
            };*/
        }

        public bool Resolve(XElement node)
        {
            return(false);
        }
    }

    public class SillyListWidget : SillyWidget
    {
        public List<SillyListItem> Items { get; private set; }

        public SillyListWidget(string id)
            : base(id, SillyType.List)
        {
            Items = new List<SillyListItem>();
        }

        public void AddItem(SillyListItem item)
        {
            Items.Add(item);
        }

        protected override bool Resolve(XElement node, SillyTargets target)
        {
            // validate the node's flavor matches this flavor
            // foreach item that's been added:
            //   call the item's resolve passing in all the child elements of node
            Console.WriteLine("SillyList");
            
            return(false);
        }
    }
}