using System;
using System.Threading.Tasks;
using SillyWidgets.Utilities.Server;
using PlannedOnIndex;

namespace local
{
    class Program
    {
        static void Main(string[] args)
        {
            Index index = new Index();
            SillySiteServer server = new SillySiteServer(index);

            Task serve = server.Start();

            serve.Wait();
        }
    }
}
