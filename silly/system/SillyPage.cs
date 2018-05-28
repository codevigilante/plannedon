using System;
using System.Text;
using System.Xml.Linq;
using System.Collections.Generic;
using Amazon.Lambda.Core;

namespace SillyWidgets
{
    public enum SillyContentType { Html, Json }

    public abstract class SillyPage : SillyWidget
    {
        public virtual SillyContentType ContentType { get; set; }

        public override string Html
        { 
            get
            {
                if (String.IsNullOrEmpty(_content))
                {
                    return(base.Html);
                }

                return(_content);
            } 
            set
            {
                _content = value;
            }
        }

        public string Route { get; set; }

        public bool Tainted { get; }
        public string TaintMessage
        {
            get
            {
                return(TaintString.ToString());
            }
        }

        private StringBuilder TaintString = new StringBuilder();
        private string _content = string.Empty;
        private static string StarterXml = "<html></html>";

        public SillyPage(string route)
            : base(route, SillyType.Page)
        {
            try
            {
                Route = route;
                ContentType = SillyContentType.Html;
                Tainted = false;

                Compile();
            }
            catch(Exception ex)
            {
                TaintString.Append(this.GetType().Name);
                TaintString.Append(" has an irreconcilable problem -> ");
                TaintString.Append(ex.Message);
                Tainted = true;
            }
        }

        public abstract bool Accept(SillyProxyRequest request, ILambdaContext context, string[] urlParams);

        protected override bool Accept(XElement element)
        {
            Console.WriteLine("Page, wait, what?");
            
            return(false);
        }

        protected virtual void Compile()
        {
            string type = this.GetType().Name;
            Stack<SillyResourceInfo> pages = null;
            Dictionary<string, HashSet<XElement>> virtualMap = new Dictionary<string, HashSet<XElement>>();

            if (SillyResourceBucket.FetchAllInherited(type, out pages))
            {
                Document = XDocument.Parse(StarterXml);

                XElement head = null, body = null, lastError = null;

                while (pages.Count != 0)
                {
                    SillyResourceInfo page = pages.Pop();

                    if (head == null)
                    {
                        XElement sourceHead = page.ShtmlDoc.Root.Element("head");

                        if (sourceHead != null)
                        {
                            if (lastError != null)
                            {
                                lastError.AddAfterSelf(sourceHead);
                            }
                            else
                            {
                                Document.Root.AddFirst(sourceHead);
                            }
                                                        
                            head = Document.Root.Element("head");
                        }
                    }

                    if (body == null)
                    {
                        XElement sourceBody = page.ShtmlDoc.Root.Element("body");

                        if (sourceBody != null)
                        {
                            Document.Root.Add(sourceBody);
                            body = Document.Root.Element("body");
                        }
                    }

                    foreach (XElement overrideElement in page.ShtmlDoc.Root.Descendants("SillyOverride"))
                    {
                        string id = overrideElement.Attribute("id").Value;
                        HashSet<XElement> virtualElements = null;

                        if (virtualMap.TryGetValue(id, out virtualElements))
                        {
                            foreach(XElement ele in virtualElements)
                            {
                                ele.RemoveNodes();
                                ele.AddAfterSelf(overrideElement.Elements());
                                ele.Remove();
                            }

                            virtualMap.Remove(id);
                        }
                        else
                        {
                            lastError = CreateError(lastError, page.ResourceString, "No virtual element to override '" + id + "'.");
                        }                
                    }

                    foreach(XElement virtualElement in Document.Root.Descendants("SillyVirtual"))
                    {
                        string id = virtualElement.Attribute("id").Value;

                        if (id == null)
                        {
                            // empty ids are OK
                            id = string.Empty;
                        }

                        if (!virtualMap.ContainsKey(id))
                        {
                            virtualMap[id] = new HashSet<XElement>();
                        }

                        if (!virtualMap[id].Contains(virtualElement))
                        {
                            virtualMap[id].Add(virtualElement);
                        }
                    }
                }

                foreach(HashSet<XElement> leftoverVirtuals in virtualMap.Values)
                {
                    foreach(XElement leftover in leftoverVirtuals)
                    {
                        leftover.AddAfterSelf(leftover.Elements());
                        leftover.Remove();
                    }
                }

                //foreach(XElement widget in base.Document.)
            }
        }

        private XElement CreateError(XElement lastErrorElement, string source, string error)
        {
            XElement errorElement = new XElement("SillyError");

            errorElement.SetAttributeValue("source", source);
            errorElement.SetAttributeValue("description", error);

            if (lastErrorElement != null)
            {
                lastErrorElement.AddAfterSelf(errorElement);
            }
            else
            {
                Document.Root.AddFirst(errorElement);
            }

            return(errorElement);
        }
    }
}