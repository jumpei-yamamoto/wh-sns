import React, { useState, useEffect } from "react";
import axios from "axios";
import { useParams, useNavigate } from "react-router-dom";

const ThreadPage = () => {
  const { threadId } = useParams();
  const [thread, setThread] = useState(null);
  const [posts, setPosts] = useState([]);
  const [newPost, setNewPost] = useState("");
  const navigate = useNavigate();
  const userId = localStorage.getItem("userId");

  const fetchThread = async () => {
    try {
      const response = await axios.get(
        `http://localhost/sns/backend/getThreads.php?id=${threadId}`
      );
      setThread(response.data.thread);
    } catch (error) {
      console.error("There was an error fetching the thread!", error);
    }
  };

  const fetchPosts = async () => {
    try {
      const response = await axios.get(
        `http://localhost/sns/backend/getThreadPosts.php?threadId=${threadId}`
      );
      setPosts(response.data.posts);
    } catch (error) {
      console.error("There was an error fetching the posts!", error);
    }
  };

  useEffect(() => {
    fetchThread();
    fetchPosts();
  }, [threadId]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(
        "http://localhost/sns/backend/createThreadPost.php",
        { threadId, userId, content: newPost }
      );
      if (response.data.success) {
        setNewPost("");
        fetchPosts();
      } else {
        alert("Failed to create post: " + response.data.message);
      }
    } catch (error) {
      alert("An error occurred: " + error.message);
      console.error("There was an error creating the post!", error);
    }
  };

  return (
    <div className="min-h-screen flex flex-col items-center bg-gray-100">
      <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
        <button
          onClick={() => navigate("/threads")}
          className="mb-4 bg-blue-500 text-white p-2 rounded"
        >
          Back to Threads
        </button>
        {thread && (
          <div>
            <h1 className="text-2xl font-bold mb-4">{thread.title}</h1>
            <p>{thread.description}</p>
            <p className="text-gray-600">
              by {thread.username} on {thread.created_at}
            </p>
          </div>
        )}
      </div>
      <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
        <h2 className="text-xl font-bold mb-4">Posts</h2>
        {posts.length > 0 ? (
          posts.map((post) => (
            <div key={post.id} className="bg-gray-200 p-4 rounded mb-4 w-full">
              <p>{post.content}</p>
              <p className="text-gray-600">
                by {post.username} on {post.created_at}
              </p>
            </div>
          ))
        ) : (
          <p>No posts available.</p>
        )}
      </div>
      <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
        <h2 className="text-xl font-bold mb-4">Add a Post</h2>
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <textarea
              value={newPost}
              onChange={(e) => setNewPost(e.target.value)}
              className="mt-1 block w-full p-2 border border-gray-300 rounded"
              required
            />
          </div>
          <button
            type="submit"
            className="w-full bg-blue-500 text-white p-2 rounded"
          >
            Post
          </button>
        </form>
      </div>
    </div>
  );
};

export default ThreadPage;
