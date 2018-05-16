using Xunit;
using System;
using System.Collections.Generic;
using System.IO;
using SillyWidgets;
using System.Diagnostics;
using Amazon.Lambda.Core;

namespace system.test
{
    public class Home : SillyPage
    {
        public Home()
            : base()
        {

        }

        public Home(string segment, string prefix = "")
            : base("home")
        {

        }
    }

    public class Blog : SillyPage
    {
        public Blog()
            : base("Blog")
        {

        }
    }

    public class Admin : SillyPage
    {
        public Admin()
            : base("admin")
        {

        }
    }

    public class ProxyTests : SillyLambda
    {
        public ProxyTests()
            : base()
        {
            AddPattern(SupportedHttpMethods.Get, @"^(\/)$", this.Root);
            AddPattern(SupportedHttpMethods.Get, @"^(\/blog)$", this.Blog);
            AddPattern(SupportedHttpMethods.Post, @"^(\/admin[\/]?[a-zA-Z-]*)$", this.Admin);
        }

        public ISillyPage Root(ILambdaContext context)
        {
            return(new Home());
        }

        public ISillyPage Blog(ILambdaContext context)
        {
            return(new Blog());
        }
        public ISillyPage Admin(ILambdaContext context)
        {
            return(new Admin());
        }

        [Fact]
        public void UrlDecodeTests()
        {
            Console.WriteLine("--- URL Decode Tests ---");

            Dictionary<string, string> EncodedToDecoded = new Dictionary<string, string>()
            {
                { @"Hello%20Gunter%20%2B%20%7B%7D%20is%20a%20whore%5B%5D", @"Hello Gunter + {} is a whore[]" },
                { @"Hello++++++Gunter+is%5fit%5fme", @"Hello      Gunter is_it_me"},
                { @"Hamburger%20made%20a%20%2C%20peromi0oi3n%20234oj%2054325%204g5%20v%20oi4h5v%204hv5gl%3B324m%20q3'5gm", @"Hamburger made a , peromi0oi3n 234oj 54325 4g5 v oi4h5v 4hv5gl;324m q3'5gm" },
                { @"~!%40%23%24%25%5E%26*()_%2B%7D%7C%7B%22%3A%3F%3E%3Cmnb%20SDF%20WETRB%20W4B%20W45G%20W45G%20124%601234%2F.%2C%2F.%2C%3BSKLDFOJKNB4T", "~!@#$%^&*()_+}|{\":?><mnb SDF WETRB W4B W45G W45G 124`1234/.,/.,;SKLDFOJKNB4T"}
            };

            int iterations = 100000;
            Stopwatch decodeTime = new Stopwatch();
            decodeTime.Start();
            for(int i = 0; i < iterations; ++i)
            {
                foreach(KeyValuePair<string, string> str in EncodedToDecoded)
                {
                    string val = WebUtilityGizmo.UrlDecode(str.Key);

                    Assert.Equal<string>(str.Value, val);
                }
            }
            decodeTime.Stop();
            Console.WriteLine("Decode time (" + iterations + "): " + decodeTime.Elapsed.TotalMilliseconds + "ms");
        }

        [Fact]
        public void DispatchTests()
        {
            Console.WriteLine("--- Dipatch Tests ---");

            Dispatch("/", "get", typeof(Home));
            Dispatch("/", "post", typeof(Home), true);
            Dispatch("/something", "get", typeof(Home), true);
            Dispatch("/blog", "get", typeof(Blog));
            Dispatch("/blog/hello", "get", typeof(Blog), true);
            Dispatch("/blog", "post", typeof(Blog), true);
            Dispatch("/admin", "post", typeof(Admin));
            Dispatch("/admin/hello-world", "post", typeof(Admin));
            Dispatch("/admin/hello-world/somethingelse", "post", typeof(Admin), true);
            Dispatch("/admin", "get", typeof(Admin), true);

            /*Map(new Home()); // /home
            Dispatch("/home", typeof(Home));
            Map(new Blog()); // /blog
            Dispatch("/blog", typeof(Blog));
            Map(new Admin()); // /admin
            Dispatch("/admin", typeof(Admin));
            Map(new Home("seg1")); // /seg1
            Dispatch("/seg1", typeof(Home));
            Map(new Blog("seg2")); // /seg2
            Dispatch("/seg2", typeof(Blog));
            Map(new Admin("seg3")); // /seg3
            Dispatch("/seg3", typeof(Admin));
            Map(new Home(), true);
            Dispatch("/penis", typeof(Home), true);
            Map(new Home("", "/folder")); // /folder/home
            Dispatch("/folder/home", typeof(Home));
            Map(new Blog("", "/folder")); // /folder/blog
            Dispatch("/folder/blog", typeof(Blog));
            Map(new Admin("", true, "/folder/taco/anus")); // /folder/taco/anus/admin/{var}/{var2}
            Dispatch("/folder/taco/anus/admin", typeof(Admin));
            Dispatch("/folder/taco/anus/admin/10/sex", typeof(Admin));
            Dispatch("/folder/taco/anus/45/62", typeof(Admin), true);
            Map(new Home("", "/folder"), true);
            Dispatch("/folder/taco/anus/home", typeof(Home), true);
            Map(new Blog("", "/home")); // /home/blog
            Dispatch("/home/blog", typeof(Blog));
            Dispatch("/home", typeof(Home));*/
        }

        /*
        [Fact]
        public void DispatchHomeTest()
        {
            Console.WriteLine("--- DispatchHome Tests ---");

            Dispatch("/", typeof(Home));
        }*/

        [Fact]
        public void ValidHtmlTests()
        {
            Console.WriteLine("--- ValidHTML Tests ---");

            SillyHtmlDocument html = new SillyHtmlDocument();

            Console.Write("Loading simple.html 100 times from assembly...");
            Stopwatch timer = new Stopwatch();
            timer.Start();
            for(int i = 0; i < 100; ++i)
            {
                bool success = html.LoadFromAssembly("system.test.testdata.simple.html");
                Assert.True(success);
                string htmlData = html.ToHtml();
                Assert.False(String.IsNullOrEmpty(htmlData));                    
            }
            timer.Stop();

            Console.WriteLine(timer.Elapsed.TotalMilliseconds + "ms");

            /*Stream fileStream = new FileStream("testdata/simple.html", FileMode.Open);

            using (StreamReader reader = new StreamReader(fileStream))
            {
                Console.Write("Parsing simple.html 100 times...");
                Stopwatch timer = new Stopwatch();
                timer.Start();
                for(int i = 0; i < 100; ++i)
                {
                    timer.Stop();
                    fileStream.Seek(0, SeekOrigin.Begin);
                    reader.DiscardBufferedData();
                    timer.Start();
                    bool success = html.Load(reader);
                    Assert.True(success);
                    string htmlData = html.ToHtml();
                    Assert.False(String.IsNullOrEmpty(htmlData));                    
                }
                timer.Stop();

                Console.WriteLine(timer.ElapsedMilliseconds + "ms " + timer.ElapsedMilliseconds / 100 + "ms avg.");                
            }

            fileStream = new FileStream("testdata/google.html", FileMode.Open);

            using (StreamReader reader = new StreamReader(fileStream))
            {
                Console.Write("Parsing google.html...");
                Stopwatch timer = new Stopwatch();
                timer.Start();
                bool success = html.Load(reader);
                timer.Stop();

                Console.WriteLine("Parse time: " + timer.ElapsedMilliseconds + "ms");

                Assert.True(success);
            }

            fileStream = new FileStream("testdata/silly.html", FileMode.Open);

            using (StreamReader reader = new StreamReader(fileStream))
            {
                Console.Write("Parsing silly.html...");
                Stopwatch timer = new Stopwatch();
                timer.Start();
                bool success = html.Load(reader);
                Assert.True(success);
                string htmlData = html.ToHtml();
                Assert.False(String.IsNullOrEmpty(htmlData)); 
                timer.Stop();

                Console.WriteLine("Parse time: " + timer.ElapsedMilliseconds + "ms");
            }*/
            
            Console.WriteLine();
        }

        private void Dispatch(string path, string method, Type viewType, bool shouldFail = false)
        {
            Console.Write("Dispatch " + method + " " + path + "...");
            
            try
            {
                SillyProxyRequest request = new SillyProxyRequest();
                request.path = path;
                request.httpMethod = method;

                ISillyPage page = base.Dispatch(request, null);

                if (page == null)
                {
                    throw new SillyException(SillyHttpStatusCode.BadRequest, "View was null");
                }

                Assert.True(viewType == page.GetType());
                Console.WriteLine(page.GetType());
            }
            catch(SillyException sillyEx)
            {
                Console.WriteLine(sillyEx.Message);

                if (shouldFail)
                {
                    Assert.True(true);
                }
                else
                {
                    Assert.True(false);
                }
            }
        }        
    }
}