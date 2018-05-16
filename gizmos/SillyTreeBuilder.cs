using System;
using System.Text;
using System.Collections.Generic;

namespace SillyWidgets
{
    public class SillyTreeBuilder
    {
        public SillyElementNode Root { get; private set; }

        private SillyElementNode Current = null;

        public SillyTreeBuilder()
        {            
            Root = null;
            Current = null;
        }

        public void CreateRoot()
        {
            Root = new SillyElementNode("root");
            Current = Root;
        }

        public SillyElementNode AddChildElement(string name)
        {
            IsCurrent();

            //Console.WriteLine("Element: " + name);

            SillyElementNode node = new SillyElementNode(name);

            Current.AddChild(node);
            Current = node;

            return(node);
        }

        public SillyWidgetNode AddWidgetElement(string sillyNamespace, string sillyTag)
        {
            IsCurrent();

            SillyWidgetNode node = new SillyWidgetNode(sillyNamespace, sillyTag);

            Current.AddChild(node);
            Current = node;

            return(node);
        }

        public SillyTextNode AddChildText(string text)
        {
            IsCurrent();

            SillyTextNode node = new SillyTextNode(text);
            
            Current.AddChild(node);

            return(node);
        }

        public void CompleteElement(string tagName = "")
        {
            IsCurrent();

            if (!String.IsNullOrEmpty(tagName))
            {
                SillyElementNode Saver = Current;
                bool found = false;

                while(Current.Parent != null)
                {
                    if (!IsNameEqual(tagName))
                    {
                        Current = Current.Parent as SillyElementNode;
                    }
                    else
                    {
                        found = true;

                        break;
                    }
                }

                if (!found)
                {
                    // don't move Current up because this is probably a dangling close tag
                    Current = Saver;

                    return;
                }

                Current.HasCloseTag = true;
            }
            else
            {
                Current.SelfCloseTag = true;
            }

            Current = Current.Parent as SillyElementNode;
        }

        private bool IsNameEqual(string name)
        {
            IsCurrent();

            return(String.Compare(Current.Name, name, false) == 0);
        }

        public void AddAttribute(string name, string value)
        {
            IsCurrent();

            Current.SetAttribute(name, value);
        }

        public void AddSillyAttribute(string sillyNamespace, string sillyType, string value)
        {
            IsCurrent();

            SillyAttribute attr = new SillyAttribute(sillyNamespace, sillyType, value, SillyAttribute.SillyLocation.Attribute);

            Current.SetSilly(attr);
        }

        private void IsCurrent()
        {
            if (Current == null)
            {
                throw new Exception("Invalid tree structure");
            }
        }
    }
}