using System;
using System.ServiceModel;
using TransactionServer.Model;

namespace TransactionServer{
    [ServiceContract]
    public interface ITransactions{
        [OperationContract]
        Authentication authenticate(string username, string password);

        [OperationContract]
        Administrator addAdmin(string token, string data);

        [OperationContract]
        BusOwner addBusOwner(string token, string data);

        [OperationContract]
        Bus getBusOwners(string token);

        [OperationContract]
        Route getRoutesAdmin(string token);

        [OperationContract]
        Route getRoutesClient(string token);

        [OperationContract]
        Buses addBus(string token, string data);
    }
}