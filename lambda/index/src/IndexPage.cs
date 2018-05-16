namespace PlannedOnIndex
{
    public class IndexPage : BasePage
    {
        public IndexPage()
            : base("/", "Home")
        {
        }

        public override bool Accept(SillyWidgets.SillyProxyRequest request, Amazon.Lambda.Core.ILambdaContext context, string[] urlParams)
        {
            base.Accept(request, context, urlParams);
            
            return(true);
        }
    }
}