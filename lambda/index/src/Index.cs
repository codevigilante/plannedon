using System;
using Amazon.Lambda.Core;
using SillyWidgets;

namespace PlannedOnIndex
{
    public class Index : SillyLambda
    {
        public Index()
            : base()
        {
            SillyRoutes.AssignRoute(new IndexPage());
            //SillyRoutes.AssignRoute("/learn", new LearnPage());
        }
    }
}
