using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TransactionServer.Model {
    public class Administrator : CommonData {
        public AdministratorData data { get; set; }
    }

    public class AdministratorData {
        public string nic { get; set; }
        public string first { get; set; }
        public string last { get; set; }
        public string tel { get; set; }
        public string add1 { get; set; }
        public string add2 { get; set; }
        public string username { get; set; }
        public string email { get; set; }
        public string password { get; set; }
    }
}
