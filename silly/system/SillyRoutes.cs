using System;
using System.Collections.Generic;
using Amazon.Lambda.Core;

namespace SillyWidgets
{
    public static class SillyRoutes
    {
        private static Dictionary<string, SillyPage> Routes = new Dictionary<string, SillyPage>();

        // this guy probably shouldn't throw exceptions, because they won't get caught and will most
        // likely bubble up to Lambdas exception handler and not be very useful
        // ASSUMPTION: Lambda has an exception handler
        public static bool AssignRoute(SillyPage page)
        {
            string pathLower = page.Route.ToLower();

            Routes[pathLower] = page;

            return(true);
        }

        internal static SillyPage Dispatch(SillyProxyRequest request, ILambdaContext context)
        {
            if (request == null ||
                String.IsNullOrEmpty(request.path))
            {
                throw new SillyException(SillyHttpStatusCode.ServerError, "Invalid request");
            }

            SillyPage page = null;
            string pathLower = request.path.ToLower();

            if (!Routes.TryGetValue(pathLower, out page))
            {
                throw new SillyException(SillyHttpStatusCode.NotFound, "The path '" + pathLower + "' could not be found.");
            }

            page.Accept(request, context, new string[]{});

            return(page);
        }
    }
}