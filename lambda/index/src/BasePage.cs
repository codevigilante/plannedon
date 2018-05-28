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
            SillyWidget intro = new SillyWidget("intro");

            intro.Bind(new SillyTextWidget("hello", "Hello World!"));

            SillyListWidget navPages = new SillyListWidget("navPages");

            foreach(KeyValuePair<string, SillyPage> navItem in NavItems)
            {
                SillyListItem item = new SillyListItem();

                item.Bind(new SillyTextWidget("pageName", navItem.Key));
                navPages.AddItem(item);
            }

            Bind(intro);
            Bind(navPages);

            return(true);
        }
    }
}