import React from 'react';

export default function Table({ children }) {
  return (
    <table className="table-auto w-full text-left">
      {children}
    </table>
  );
}

Table.Card = function TableCard({ title, children }) {
  return (
    <div className="bg-white shadow-md rounded-lg overflow-hidden mb-6">
      {title && <div className="px-6 py-4 bg-gray-200 text-gray-700 font-semibold text-lg">{title}</div>}
      <div className="p-6">
        {children}
      </div>
    </div>
  );
};

Table.Thead = function Thead({ children }) {
  return (
    <thead className="bg-gray-200">
      {children}
    </thead>
  );
};

Table.Tbody = function Tbody({ children }) {
  return (
    <tbody>
      {children}
    </tbody>
  );
};

Table.Th = function Th({ children }) {
  return (
    <th className="px-4 py-2 text-gray-600">
      {children}
    </th>
  );
};

Table.Td = function Td({ children }) {
  return (
    <td className="border px-4 py-2">
      {children}
    </td>
  );
};
