using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TransactionServer.Model {
    public class Bus : CommonData{
        public IList<BusData> data { get; set; }
    }

    public class BusData {
        public string license_no { get; set; }
        public string tel { get; set; }
        public string fullName { get; set; }
    }
}