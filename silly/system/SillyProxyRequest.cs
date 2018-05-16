using System;
using System.Text;
using System.Collections.Generic;
using Newtonsoft.Json;

namespace SillyWidgets
{
    public class SillyProxyRequest
    {
        public string resource { get; set; }
        public string path { get; set; }
        public string httpMethod { get; set; }
        public Dictionary<string, object> queryStringParameters { get; set; }
        public Dictionary<string, object> headers { get; set; }
        public string body { get; set; }

        public SillyProxyRequest()
        {
            queryStringParameters = new Dictionary<string, object>();
            headers = new Dictionary<string, object>();
        }

        public override string ToString()
        {
            StringBuilder str = new StringBuilder();
            string queryVars = string.Empty;

            str.Append(httpMethod);
            str.Append(" ");
            str.Append(path);
            str.Append(" QUERY: ");

            foreach(KeyValuePair<string, object> param in queryStringParameters)
            {
                str.Append(param.Key);
                str.Append("=");
                str.Append(param.Value.ToString());
                str.Append(" ");
            }

            str.Append("BODY: ");
            str.Append(body);

            return(str.ToString());
        }
    }
}