using System;
using System.Collections.Generic;
using SillyWidgets;

namespace PlannedOnIndex
{
    public class BasePage : SillyPage
    {
        private static Dictionary<string, SillyPage> NavItems = new Dictionary<string, SillyPage>();

        public BasePage(string route, string navName = "")
            : base(route)
        {
            if (!String.IsNullOrEmpty(navName))
            {
                NavItems[navName] = this;
            }
        }

        public override bool Accept(SillyProxyRequest request, Amazon.Lambda.Core.ILambdaContext context, string[] urlParams)
        {
            SillyListWidget navPages = new SillyListWidget("navPages");

            foreach(KeyValuePair<string, SillyPage> navItem in NavItems)
            {
                navPages.AddItem(new SillyListItem(string.Empty)
                {
                    { "pageName", new SillyTextWidget("pageName", navItem.Key) }
                });
            }

            Bind(navPages);

            return(true);
        }
    }
}