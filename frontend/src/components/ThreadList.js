import React, { useState, useEffect } from "react";
import api from "../api";
import { Link, useNavigate } from "react-router-dom";

const ThreadList = () => {
  const [threads, setThreads] = useState([]);
  const navigate = useNavigate();

  const fetchThreads = async () => {
    try {
      const response = await api.get(
        "/getThreads.php"
      );
      setThreads(response.data.threads);
    } catch (error) {
      console.error("There was an error fetching the threads!", error);
    }
  };

  useEffect(() => {
    fetchThreads();
  }, []);

  return (
    <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
      <h1 className="text-2xl font-bold mb-4">Threads</h1>
      <button
        onClick={() => navigate("/home")}
        className="mb-4 bg-blue-500 text-white p-2 rounded"
      >
        Back to Home
      </button>
      {threads.length > 0 ? (
        threads.map((thread) => (
          <div key={thread.id} className="bg-gray-200 p-4 rounded mb-4 w-full">
            <h2 className="text-lg font-bold">
              <Link to={`/thread/${thread.id}`}>{thread.title}</Link>
            </h2>
            <p>{thread.description}</p>
            <p className="text-gray-600">
              by {thread.username} on {thread.created_at}
            </p>
          </div>
        ))
      ) : (
        <p>No threads available.</p>
      )}
    </div>
  );
};

export default ThreadList;
