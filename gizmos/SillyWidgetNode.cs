using System.Collections.Generic;

namespace SillyWidgets
{
    public class SillyWidgetNode : SillyElementNode
    {
        public override SillyAttribute SillyAttr
        { 
            get
            {
                SetId();

                return(_sillyattr);
            }
            protected set
            {
                _sillyattr = value;
            }
        }

        private SillyAttribute _sillyattr = null;

        public SillyWidgetNode(string sillyNamespace, string sillyTag)
            : base(sillyNamespace + ":" + sillyTag)
        {
            _sillyattr = new SillyAttribute(sillyNamespace, sillyTag, string.Empty);
        }

        public override void Accept(ISillyNodeVisitor visitor)
        {
            visitor.VisitWidget(this);
        }

        public override void Attach(ISillyWidget widget)
        {
            SetId();
            
            base.Attach(widget);
        }

        private void SetId()
        {
            if (string.IsNullOrEmpty(_sillyattr.Id))
            {
                string id = base.GetAttribute("id");

                _sillyattr.Id = id;
            }
        }
    }
}