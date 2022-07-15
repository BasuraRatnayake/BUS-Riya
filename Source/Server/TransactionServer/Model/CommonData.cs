using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TransactionServer.Model {
    public class CommonData {
        public bool status { get; set; }
        public int response_code { get; set; }
        public string message { get; set; }
    }
}
