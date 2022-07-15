using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TransactionServer.Model {
    public class BusOwner : CommonData {
        public BusOwnerData data { get; set; }
    }

    public class BusOwnerData {
        public string license_no { get; set; }
        public string fname { get; set; }
        public string lname { get; set; }
        public string addl_1 { get; set; }
        public string addl_2 { get; set; }
        public int tel { get; set; }
    }
}
