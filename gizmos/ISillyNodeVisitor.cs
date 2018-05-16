namespace SillyWidgets
{
    public interface ISillyNodeVisitor
    {
        void VisitElement(SillyElementNode node);
        void VisitText(SillyTextNode node);
        void VisitWidget(SillyWidgetNode node);
        void Go(SillyHtmlNode node);
        void Reset();
    }
}