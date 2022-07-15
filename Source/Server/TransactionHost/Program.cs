using System;
using System.ServiceModel;

namespace TransactionHost{
    class Program{
        protected static void login(){//Login Code
            String username = "";
            String password = "";

            while(!username.Equals("admin") && !password.Equals("mywins13")){
                Console.Clear();

                Console.WriteLine();
                Console.WriteLine();

                Console.Write("\t\tEnter Username : ");
                username = Console.ReadLine().ToLower();

                Console.Write("\t\tEnter Password : ");
                password = Console.ReadLine().ToLower();

                if(username.Equals("admin") && password.Equals("mywins13")){
                    Console.Title = "BusRiya Transaction Server 1.0 - Logged In";
                    getCommands();
                }else
                    Console.WriteLine("\t\tInvalid Login Credentials");

                Console.ReadLine();
            }
        }

        protected static void getCommands(){//Establish Commands
            Console.Clear();

            String input = "";

            Console.WriteLine();
            Console.WriteLine();
            Console.WriteLine("\t\tWELCOME TO BUSRIYA TRANSACTION SERVER");
            Console.WriteLine();

            ServiceHost host = new ServiceHost(typeof(TransactionServer.Transactions));//Server Connection

            while(!input.Equals("poweroff")){//Loops until Poweroff
                Console.Write("\t\tEnter Command : ");
                input = Console.ReadLine().ToLower();//Get Input Command
                switch(input){
                    case "start":
                        host = new ServiceHost(typeof(TransactionServer.Transactions));
                        host.Open();
                        Console.WriteLine("\t\tServer Connection Established @ " + DateTime.Now.ToString());
                        Console.WriteLine();
                        break;
                    case "state":
                        Console.WriteLine("\t\tServer Connection " + host.State);
                        Console.WriteLine();
                        break;
                    case "stop":
                        host.Close();
                        Console.WriteLine("\t\tServer Connection Closed @ " + DateTime.Now.ToString());
                        Console.WriteLine();
                        break;
                    case "clear":
                        Console.Clear();
                        Console.WriteLine();
                        break;
                    case "poweroff":
                        host.Close();
                        break;
                    case "address":
                        foreach(Uri address in host.BaseAddresses)
                            Console.WriteLine("\t\tListening on " + address);
                        Console.WriteLine();
                        break;
                    default:
                        Console.WriteLine("\t\tInvalid Command");
                        Console.WriteLine();
                        break;
                }
            }
        }

        static void Main(string[] args){
            Console.Title = "Bus Riya Administration Transaction Server 1.0 - Not Logged In";

            Console.BackgroundColor = ConsoleColor.White;
            Console.ForegroundColor = ConsoleColor.Black;

            login();

            //bool s = true;
            //ServiceHost host = new ServiceHost(typeof(TransactionServer.Transactions));//Server Connection
            //host = new ServiceHost(typeof(TransactionServer.Transactions));
            //host.Open();
            //while (s) { }
        }
    }
}