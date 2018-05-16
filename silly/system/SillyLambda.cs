using System;
using System.Text;
using System.Reflection;
using Newtonsoft.Json;
using Amazon.Lambda.Core;
using Amazon.S3;

[assembly: LambdaSerializerAttribute(typeof(Amazon.Lambda.Serialization.Json.JsonSerializer))]

namespace SillyWidgets
{
    public abstract class SillyLambda
    {
        private StringBuilder LogMessage = new StringBuilder();
        private StringBuilder ConfigMessage = new StringBuilder();
        private bool InvalidConfig = false;

        protected SillyProxyRequest CurrentRequest = null;
        protected SupportedHttpMethods Method = SupportedHttpMethods.Unsupported;

        public SillyLambda(string wwwroot = "wwwroot")
        {
            try
            {
                SillyResourceBucket.GetResources(Assembly.GetCallingAssembly(), wwwroot);
            }
            catch(Exception ex)
            {
                ConfigMessage.Append(DateTime.Now.ToString("yyyy-MM-ddTHH:mm:ss -> "));
                ConfigMessage.Append("SillyLambda Constructor -> ");
                ConfigMessage.Append(ex.Message);
                InvalidConfig = true;
            }
        }

        public SillyLambda(Amazon.RegionEndpoint endpoint, string bucket, string wwwroot = "/")
        {
            // setup the stuff in the SillyResourceBucket
        }

        //********************************************************************
        // Lambda signature: <assembly>::<namespace>.<MyProxyDerivedClass>::<method>
        //********************************************************************
        public virtual SillyProxyResponse Handle(SillyProxyRequest input, ILambdaContext lambdaContext)
        {
            if (InvalidConfig)
            {
                return(buildErrorResponse(SillyHttpStatusCode.ServerError, ConfigMessage.ToString()));
            }

            LogMessage.Clear();
            LogMessage.Append(DateTime.Now.ToString("yyyy-MM-ddTHH:mm:ss -> "));

            try
            {
                SillyPage page = SillyRoutes.Dispatch(input, lambdaContext);

                if (page == null)
                {
                    throw new SillyException(SillyHttpStatusCode.NotImplemented, "The path '" + input.path + "' is not implemented.");
                }

                if (page.Tainted)
                {
                    return(buildErrorResponse(SillyHttpStatusCode.ServerError, page.TaintMessage));
                }

                SillyProxyResponse response = new SillyProxyResponse();
                response.body = page.Html;

                LogMessage.Append(" ");
                LogMessage.Append(response.statusCode);

                return(response);
            }
            catch (SillyException sillyEx)
            {
                return(buildErrorResponse(sillyEx));
            }
            catch (Exception Ex)
            {
                return(buildErrorResponse(SillyHttpStatusCode.ServerError, Ex.Message));
            }
            finally
            {
                lambdaContext.Logger.LogLine(LogMessage.ToString());
            }
        }

        private SupportedHttpMethods StringToHttpMethod(string httpMethod)
        {
            if (String.IsNullOrEmpty(httpMethod))
            {
                return(SupportedHttpMethods.Unsupported);
            }

            if (String.Compare(httpMethod, "get", true) == 0)
            {
                return(SupportedHttpMethods.Get);
            }
            
            if (String.Compare(httpMethod, "post", true) == 0)
            {
                return(SupportedHttpMethods.Post);
            }

            return(SupportedHttpMethods.Unsupported);
        }

        private SillyProxyResponse buildErrorResponse(SillyException ex)
        {
            return(buildErrorResponse(ex.StatusCode, ex.Message));
        }

        private SillyProxyResponse buildErrorResponse(SillyHttpStatusCode statusCode, string details)
        {
            LogMessage.Append(" EXCEPTION: ");
            LogMessage.Append(statusCode);
            LogMessage.Append(" ");
            LogMessage.Append(details);

            SillyProxyResponse errorResponse = new SillyProxyResponse(statusCode);

            errorResponse.body = "<h1>Server ERROR</h1><h3>" + errorResponse.StatusCodeToString() + "</h3>";
            errorResponse.body += "<p>" + details + "</p>";

            return(errorResponse);
        }
    }
}