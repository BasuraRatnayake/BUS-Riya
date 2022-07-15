using System;
using System.Collections.Generic;
using TransactionServer.Model;
using RestSharp;
using Newtonsoft.Json;

namespace TransactionServer {
    public class Transactions:ITransactions{
        public Authentication authenticate(string username, string password) {
            var client = new RestClient("http://localhost/distribute/view/");

            var request = new RestRequest("admin_authenticate.php", Method.POST);
            request.AddParameter("username", username);
            request.AddParameter("password", password);

            IRestResponse response = client.Execute(request);
            Authentication auth = JsonConvert.DeserializeObject<Authentication>(response.Content.ToString());

            return auth;
        }
        public Administrator addAdmin(string token, string data) {
            var client = new RestClient("http://localhost/distribute/view/");
            var request = new RestRequest("add_admin.php", Method.POST);

            Administrator admin = JsonConvert.DeserializeObject<Administrator>(data);
            request.AddParameter("auth_token", token);
            request.AddParameter("nic", admin.data.nic);
            request.AddParameter("first", admin.data.first);
            request.AddParameter("last", admin.data.last);
            request.AddParameter("tel", admin.data.tel);
            request.AddParameter("add1", admin.data.add1);
            request.AddParameter("add2", admin.data.add2);
            request.AddParameter("username", admin.data.username);
            request.AddParameter("email", admin.data.email);
            request.AddParameter("password", admin.data.password);

            IRestResponse response = client.Execute(request);
            admin = JsonConvert.DeserializeObject<Administrator>(response.Content.ToString());

            return admin;
        }

        public BusOwner addBusOwner(string token, string data) {
            var client = new RestClient("http://localhost/distribute/view/");
            var request = new RestRequest("add_bus_owner.php", Method.POST);

            BusOwner owner = JsonConvert.DeserializeObject<BusOwner>(data);
            request.AddParameter("auth_token", token);
            request.AddParameter("licenseNo", owner.data.license_no);
            request.AddParameter("fname", owner.data.fname);
            request.AddParameter("lname", owner.data.lname);
            request.AddParameter("addr1", owner.data.addl_1);
            request.AddParameter("addr2", owner.data.addl_2);
            request.AddParameter("tel", owner.data.tel);

            IRestResponse response = client.Execute(request);
            owner = JsonConvert.DeserializeObject<BusOwner>(response.Content.ToString());

            return owner;
        }

        public Bus getBusOwners(string token) {
            var client = new RestClient("http://localhost/distribute/view/");
            var request = new RestRequest("get_bus_owner.php", Method.GET);

            request.AddParameter("auth_token", token);

            IRestResponse response = client.Execute(request);
            Bus bus = JsonConvert.DeserializeObject<Bus>(response.Content.ToString());

            return bus;
        }

        public Route getRoutesAdmin(string token) {
            var client = new RestClient("http://localhost/distribute/view/");
            var request = new RestRequest("get_route_admin.php", Method.GET);

            request.AddParameter("auth_token", token);

            IRestResponse response = client.Execute(request);
            Route route = JsonConvert.DeserializeObject<Route>(response.Content.ToString());

            return route;
        }

        public Route getRoutesClient(string token) {
            var client = new RestClient("http://localhost/distribute/view/");
            var request = new RestRequest("get_route_admin.php", Method.GET);

            request.AddParameter("auth_token", token);

            IRestResponse response = client.Execute(request);
            Route route = JsonConvert.DeserializeObject<Route>(response.Content.ToString());

            return route;
        }

        public Buses addBus(string token, string data) {
            var client = new RestClient("http://localhost/distribute/view/");
            var request = new RestRequest("add_bus.php", Method.POST);

            Buses bus = JsonConvert.DeserializeObject<Buses>(data);
            request.AddParameter("auth_token", token);
            request.AddParameter("bus_no", bus.data.bus_no);
            request.AddParameter("licenseNo", bus.data.owner_license);
            request.AddParameter("routeId", bus.data.route_id);
            request.AddParameter("bus_type", bus.data.bus_type);
            request.AddParameter("cur_seat_cap", bus.data.current_seats);
            request.AddParameter("seat_cap", bus.data.seats_max);

            IRestResponse response = client.Execute(request);
            bus = JsonConvert.DeserializeObject<Buses>(response.Content.ToString());

            return bus;
        }
    }
}