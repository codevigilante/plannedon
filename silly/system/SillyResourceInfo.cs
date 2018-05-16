using System;
using System.Xml.Linq;

namespace SillyWidgets
{
    internal class SillyResourceInfo : Tuple<string, XDocument>
    {
        public string ResourceString
        {
            get
            {
                return(base.Item1);
            }
        }
        public XDocument ShtmlDoc
        {
            get
            {
                return(base.Item2);
            }
        }
        
        public SillyResourceInfo(string resourceString, XDocument shtmlDoc)
            : base(resourceString, shtmlDoc)
        {

        }
    }
}