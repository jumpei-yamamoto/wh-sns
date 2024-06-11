import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api";

const CreateThread = () => {
  const [title, setTitle] = useState("");
  const [description, setDescription] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await api.post("/createThread.php", {
        title,
        description,
      });
      if (response.data.success) {
        alert("Thread created successfully");
        setTitle("");
        setDescription("");
        navigate("/home"); // ホーム画面に遷移
      } else {
        alert("Failed to create thread: " + response.data.message);
      }
    } catch (error) {
      alert("An error occurred: " + error.message);
      console.error("There was an error creating the thread!", error);
    }
  };

  return (
    <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
      <h2 className="text-xl font-bold mb-4">Create Thread</h2>
      <button
        onClick={() => navigate("/home")}
        className="mb-4 bg-blue-500 text-white p-2 rounded"
      >
        Back to Home
      </button>
      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <label className="block text-gray-700">Title</label>
          <input
            type="text"
            value={title}
            onChange={(e) => setTitle(e.target.value)}
            className="mt-1 block w-full p-2 border border-gray-300 rounded"
            required
          />
        </div>
        <div className="mb-4">
          <label className="block text-gray-700">Description</label>
          <textarea
            value={description}
            onChange={(e) => setDescription(e.target.value)}
            className="mt-1 block w-full p-2 border border-gray-300 rounded"
          />
        </div>
        <button
          type="submit"
          className="w-full bg-blue-500 text-white p-2 rounded"
        >
          Create Thread
        </button>
      </form>
    </div>
  );
};

export default CreateThread;
