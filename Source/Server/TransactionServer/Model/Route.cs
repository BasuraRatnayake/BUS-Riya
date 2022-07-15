using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TransactionServer.Model {
    public class Route : CommonData {
        public IList<RouteData> data { get; set; }
    }

    public class RouteData {
        public string route_id { get; set; }
        public string route_no { get; set; }
        public string route_start { get; set; }
        public string route_end { get; set; }
    }
}
