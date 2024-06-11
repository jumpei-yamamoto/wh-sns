import React, { useState, useEffect } from "react";
import api from "../api";
import { useNavigate } from "react-router-dom";

const Notifications = () => {
  const [notifications, setNotifications] = useState([]);
  const navigate = useNavigate();

  const fetchNotifications = async () => {
    try {
      const response = await api.get("/getNotifications.php");
      setNotifications(response.data.notifications);
    } catch (error) {
      console.error("There was an error fetching the notifications!", error);
    }
  };

  useEffect(() => {
    fetchNotifications();
  }, []);

  const markAsRead = async (notificationId) => {
    try {
      await api.post("/markAsRead.php", {
        notificationId,
      });
      fetchNotifications();
    } catch (error) {
      console.error(
        "There was an error marking the notification as read!",
        error
      );
    }
  };

  return (
    <div className="min-h-screen flex flex-col items-center bg-gray-100">
      <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
        <h1 className="text-2xl font-bold mb-4">Notifications</h1>
        <button
          onClick={() => navigate("/home")}
          className="mb-4 bg-blue-500 text-white p-2 rounded"
        >
          Back to Home
        </button>
        {notifications.length > 0 ? (
          notifications.map((notification) => (
            <div
              key={notification.id}
              className={`bg-gray-200 p-4 rounded mb-4 w-full ${
                notification.is_read ? "opacity-50" : ""
              }`}
            >
              <p>{notification.content}</p>
              <p className="text-gray-600">{notification.created_at}</p>
              {!notification.is_read ? (
                <button
                  onClick={() => markAsRead(notification.id)}
                  className="bg-blue-500 text-white p-2 rounded mt-2"
                >
                  Mark as Read
                </button>
              ) : (
                <span className="text-green-500 text-xl">✔️</span>
              )}
            </div>
          ))
        ) : (
          <p>No notifications available.</p>
        )}
      </div>
    </div>
  );
};

export default Notifications;
