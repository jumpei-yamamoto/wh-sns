import React, { useState, useEffect } from "react";
import api from "../api";
import { useNavigate } from "react-router-dom";

const MessagesList = () => {
  const [messages, setMessages] = useState([]);
  const userId = localStorage.getItem("userId");
  const navigate = useNavigate();

  const fetchMessages = async () => {
    try {
      const response = await api.get(
        `/getReceivedMessages.php?userId=${userId}`
      );
      setMessages(response.data.messages);
    } catch (error) {
      console.error("There was an error fetching the messages!", error);
    }
  };

  useEffect(() => {
    fetchMessages();
  }, []);

  return (
    <div className="min-h-screen flex flex-col items-center bg-gray-100">
      <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
        <h1 className="text-2xl font-bold mb-4">Messages</h1>
        <button
          onClick={() => navigate("/home")}
          className="mb-4 bg-blue-500 text-white p-2 rounded"
        >
          Back to Home
        </button>
        {messages.length > 0 ? (
          messages.map((message) => (
            <div
              key={message.id}
              className="bg-gray-200 p-4 rounded mb-4 w-full"
            >
              <p>
                <strong>From: {message.sender}</strong>
              </p>
              <p>{message.content}</p>
              <p className="text-gray-600">{message.created_at}</p>
            </div>
          ))
        ) : (
          <p>No messages available.</p>
        )}
      </div>
    </div>
  );
};

export default MessagesList;
