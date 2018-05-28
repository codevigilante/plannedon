using System;
using System.Collections.Generic;
using System.Xml.Linq;
using System.Reflection;

namespace SillyWidgets
{
    internal static class SillyResourceBucket
    {
        private static Assembly SourceAssembly = null;
        private static string WWWRoot = string.Empty;
        private static Dictionary<string, SillyResourceInfo> ShtmlCache = new Dictionary<string, SillyResourceInfo>();

        public static void GetResources(Assembly source, string wwwroot)
        {
            SourceAssembly = source;
            WWWRoot = wwwroot;

            string[] embeddedResources = SourceAssembly.GetManifestResourceNames();

            foreach(string resource in embeddedResources)
            {
                using (var resourceStream = SourceAssembly.GetManifestResourceStream(resource))
                {
                    // we really only want to look at resources that are under WWWRoot
                    if (resource.EndsWith("shtml", StringComparison.OrdinalIgnoreCase))
                    {
                        XDocument doc = XDocument.Load(resourceStream);        
                        
                        if (!TryAddToCache("page", doc, resource))
                        {
                            if (!TryAddToCache("widget", doc, resource))
                            {
                                throw new SillyException(SillyHttpStatusCode.ServerError, "shtml attribute 'page' or 'widget' must be specified on root html element in '" + resource + "'.");
                            }
                        }

                        Console.WriteLine("Added resource: " + resource);
                    }
                }
            }
        }

        private static bool TryAddToCache(string attr, XDocument doc, string resource)
        {
            string key = doc.Root.Attribute(attr)?.Value;

            if (String.IsNullOrEmpty(key))
            {
                return(false);
            }

            if (!ShtmlCache.ContainsKey(key))
            {
                ShtmlCache.Add(key, new SillyResourceInfo(resource, doc));
            }
            else
            {
                throw new SillyException(SillyHttpStatusCode.ServerError, "Duplicate shtml types detected: '" + key + " -> " + resource + "'. shtml types must be unique.");
            }

            return(true);
        }

        public static bool FetchAllInherited(string derivedKey, out Stack<SillyResourceInfo> resources)
        {
            resources = new Stack<SillyResourceInfo>();

            SillyResourceInfo info = null;
            string currentKey = derivedKey;
            bool found = Fetch(currentKey, out info);

            // this doesn't check for circular inheritance, and if it's present, it won't be obvious
            // so, probably should check for that shit
            while (found)
            {
                resources.Push(info);
                currentKey = info.ShtmlDoc.Root.Attribute("inherits")?.Value;

                if (String.IsNullOrEmpty(currentKey))
                {
                    break;
                }

                found = Fetch(currentKey, out info);

                if (!found)
                {
                    throw new SillyException(SillyHttpStatusCode.ServerError, "Cannot resolve inherited page '" + currentKey + "' inherited by '" + derivedKey + "'.");
                }
            }

            return(resources.Count > 0);
        }

        public static bool Fetch(string key, out SillyResourceInfo info)
        {
            info = null;

            return(ShtmlCache.TryGetValue(key, out info));
        }
    }
}