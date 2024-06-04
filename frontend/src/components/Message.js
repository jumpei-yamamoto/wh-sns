import React, { useState, useEffect } from "react";
import api from "../api";
import { useParams, useNavigate } from "react-router-dom";

const Message = () => {
  const { receiverId } = useParams(); // URLパラメータからreceiverIdを取得
  const [messages, setMessages] = useState([]);
  const [newMessage, setNewMessage] = useState("");
  const senderId = localStorage.getItem("userId");
  const navigate = useNavigate();

  const fetchMessages = async () => {
    try {
      const response = await api.get(
        `/getMessages.php?senderId=${senderId}&receiverId=${receiverId}`
      );
      setMessages(response.data.messages);
    } catch (error) {
      console.error("There was an error fetching the messages!", error);
    }
  };

  useEffect(() => {
    fetchMessages();
  }, [receiverId]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await api.post(
        "/sendMessage.php",
        { senderId, receiverId, content: newMessage }
      );
      if (response.data.success) {
        setNewMessage("");
        fetchMessages();
      } else {
        alert("Failed to send message: " + response.data.message);
      }
    } catch (error) {
      alert("An error occurred: " + error.message);
      console.error("There was an error sending the message!", error);
    }
  };

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
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <textarea
              value={newMessage}
              onChange={(e) => setNewMessage(e.target.value)}
              className="mt-1 block w-full p-2 border border-gray-300 rounded"
              required
            />
          </div>
          <button
            type="submit"
            className="w-full bg-blue-500 text-white p-2 rounded"
          >
            Send Message
          </button>
        </form>
        <div className="mt-8">
          {messages.length > 0 ? (
            messages.map((message) => (
              <div
                key={message.id}
                className="bg-gray-200 p-4 rounded mb-4 w-full"
              >
                <p>
                  <strong>{message.sender}:</strong> {message.content}
                </p>
                <p className="text-gray-600">{message.created_at}</p>
              </div>
            ))
          ) : (
            <p>No messages available.</p>
          )}
        </div>
      </div>
    </div>
  );
};

export default Message;
