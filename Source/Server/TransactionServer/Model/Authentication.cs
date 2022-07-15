using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TransactionServer.Model {
    public class Token {
        public string auth_token { get; set; }
        public string refresh_token { get; set; }
        public string username { get; set; }
    }

    public class Authentication : CommonData {
        public Token token { get; set; }
        public string auth_token {
            get {
                return token == null ? "" : token.auth_token;
            }
            set {
                token.auth_token = value;
            }
        }
        public string refresh_token {
            get {
                return token == null ? "" : token.refresh_token;
            }
            set {
                token.refresh_token = value;
            }
        }
        public string username {
            get {
                return token == null ? "" : token.username;
            }
            set {
                token.username = value;
            }
        }
    }
}
