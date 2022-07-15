using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TransactionServer.Model {
    public class Buses : CommonData{
        public BusesData data { get; set; }
    }

    public class BusesData {
        public string bus_no { get; set; }
        public string owner_license { get; set; }
        public int route_id { get; set; }
        public string current_seats { get; set; }
        public string seats_max { get; set; }
        public string bus_type { get; set; }
    }
}
