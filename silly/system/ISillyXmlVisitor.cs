using System;
using System.Xml.Linq;

namespace SillyWidgets
{
    public interface ISillyXmlVisitor
    {
         void Visit(XElement element);
         void Visit(XComment comment);
         void Visit(XText text);
         void Visit(XNode node);
    }
}