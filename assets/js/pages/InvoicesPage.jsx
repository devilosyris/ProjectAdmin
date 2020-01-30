import React, { useEffect, useState } from "react";
import Axios from "axios";
import moment from "moment";

const InvoicesPage = props => {

    const [invoices, setInvoices] = useState([]);
    useEffect(()=> {
        Axios.get("http://localhost:8000/api/invoices").then(response => response.data['hydra:member'])
        .then(data => setInvoices(data));
    },[])

    const formatDate = (str) => moment(str).format('DD/MM/YYYY');

    return <>
      <h1>Liste des invoices</h1>
      <table className="table table-hover table-responsive">
      <thead>
          <tr>
              <th>Nom</th>
              <th>Montant</th>
              <th>Date</th>
              <th>Date limite</th>
              <th>Statut</th>
              <th>PDF</th>
              <th>Utilisateurs</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
          {invoices.map(invoice => (
            <tr key={invoice.id}>
                <td>{invoice.name}</td>
                <td>{invoice.amount.toLocaleString()} €</td>
                <td>{formatDate(invoice.createdAt)}</td>
                <td>{formatDate(invoice.expiry)}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
          ))}
      </tbody>
      </table>
    </>;
}
export default InvoicesPage;