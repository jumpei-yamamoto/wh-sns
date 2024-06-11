import React, { useState, useEffect } from "react";
import { useNavigate, Link } from "react-router-dom";
import CreatePost from "./CreatePost";
import api from "../api";

const HomeFeed = () => {
  const [posts, setPosts] = useState([]);
  const navigate = useNavigate();

  const fetchPosts = async () => {
    try {
      const response = await api.get("/getPosts.php");
      setPosts(response.data.posts);
    } catch (error) {
      console.error("There was an error fetching the posts!", error);
    }
  };

  useEffect(() => {
    fetchPosts();
  }, []);

  const handleSignOut = async () => {
    try {
      const response = await api.post("/logout.php");
      if (response.data.success) {
        navigate("/login");
      } else {
        alert("Failed to log out: " + response.data.message);
      }
    } catch (error) {
      alert("An error occurred: " + error.message);
      console.error("There was an error logging out!", error);
    }
  };

  return (
    <div className="min-h-screen flex flex-col items-center bg-gray-100">
      <div className="w-full max-w-2xl mt-8 flex justify-between items-center">
        <h1 className="text-2xl font-bold">Home Feed</h1>
        <Link to="/settings" className="bg-blue-500 text-white p-2 rounded">
          Settings
        </Link>
        <button
          onClick={handleSignOut}
          className="bg-red-500 text-white p-2 rounded"
        >
          Sign Out
        </button>
      </div>
      <div className="w-full max-w-2xl mt-4 flex justify-between">
        <Link to="/threads" className="bg-blue-500 text-white p-2 rounded">
          View Threads
        </Link>
        <Link
          to="/create-thread"
          className="bg-green-500 text-white p-2 rounded"
        >
          Create Thread
        </Link>
        <Link
          to="/notifications"
          className="bg-yellow-500 text-white p-2 rounded"
        >
          View Notifications
        </Link>
        <Link to="/search" className="bg-purple-500 text-white p-2 rounded">
          Search
        </Link>
        <Link to="/messages" className="bg-teal-500 text-white p-2 rounded">
          View Messages
        </Link>
      </div>
      <CreatePost onPostCreated={fetchPosts} />
      <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
        {posts.length > 0 ? (
          posts.map((post) => (
            <div key={post.id} className="bg-gray-200 p-4 rounded mb-4 w-full">
              <div className="flex items-center mb-2">
                <img
                  src={`${process.env.REACT_APP_API_BASE_URL}/${post.profile_picture}`}
                  alt="Profile"
                  className="w-10 h-10 rounded-full mr-2"
                />
                <div>
                  <h2 className="text-lg font-bold">{post.username}</h2>
                  <p className="text-gray-600">{post.created_at}</p>
                  <Link
                    to={`/message/${post.user_id}`}
                    className="text-blue-500"
                  >
                    Send Message
                  </Link>
                </div>
              </div>
              <p>{post.content}</p>
              {post.image && (
                <img
                  src={`${process.env.REACT_APP_API_BASE_URL}/${post.image}`}
                  alt="Post"
                  className="mt-2 max-w-full"
                />
              )}
            </div>
          ))
        ) : (
          <p>No posts available.</p>
        )}
      </div>
    </div>
  );
};

export default HomeFeed;
