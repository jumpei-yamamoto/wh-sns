import React, { useState } from "react";
import api from "../api";
import { useNavigate } from "react-router-dom";

const Search = () => {
  const [query, setQuery] = useState("");
  const [results, setResults] = useState({ users: [], posts: [], threads: [] });
  const navigate = useNavigate();

  const handleSearch = async (e) => {
    e.preventDefault();
    try {
      const response = await api.get(`/search.php?query=${query}`);
      setResults(response.data);
    } catch (error) {
      console.error("There was an error performing the search!", error);
    }
  };

  return (
    <div className="min-h-screen flex flex-col items-center bg-gray-100">
      <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
        <h1 className="text-2xl font-bold mb-4">Search</h1>
        <button
          onClick={() => navigate("/home")}
          className="mb-4 bg-blue-500 text-white p-2 rounded"
        >
          Back to Home
        </button>
        <form onSubmit={handleSearch}>
          <div className="mb-4">
            <input
              type="text"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              className="mt-1 block w-full p-2 border border-gray-300 rounded"
              placeholder="Search for users, posts, or threads..."
              required
            />
          </div>
          <button
            type="submit"
            className="w-full bg-blue-500 text-white p-2 rounded"
          >
            Search
          </button>
        </form>
        <div className="mt-8">
          {results.users.length > 0 && (
            <div className="mb-8">
              <h2 className="text-xl font-bold mb-4">Users</h2>
              {results.users.map((user) => (
                <div key={user.id} className="bg-gray-200 p-4 rounded mb-4">
                  <img
                    src={`${process.env.REACT_APP_API_BASE_URL}/${user.profile_picture}`}
                    alt={user.username}
                    className="w-10 h-10 rounded-full mr-2"
                  />
                  <span>{user.username}</span>
                </div>
              ))}
            </div>
          )}
          {results.posts.length > 0 && (
            <div className="mb-8">
              <h2 className="text-xl font-bold mb-4">Posts</h2>
              {results.posts.map((post) => (
                <div key={post.id} className="bg-gray-200 p-4 rounded mb-4">
                  <div className="flex items-center mb-2">
                    <img
                      src={`/sns/backend/${post.profile_picture}`}
                      alt={post.username}
                      className="w-10 h-10 rounded-full mr-2"
                    />
                    <div>
                      <h3 className="text-lg font-bold">{post.username}</h3>
                      <p className="text-gray-600">{post.created_at}</p>
                    </div>
                  </div>
                  <p>{post.content}</p>
                </div>
              ))}
            </div>
          )}
          {results.threads.length > 0 && (
            <div className="mb-8">
              <h2 className="text-xl font-bold mb-4">Threads</h2>
              {results.threads.map((thread) => (
                <div key={thread.id} className="bg-gray-200 p-4 rounded mb-4">
                  <h3 className="text-lg font-bold">{thread.title}</h3>
                  <p>{thread.description}</p>
                  <p className="text-gray-600">
                    by {thread.username} on {thread.created_at}
                  </p>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Search;
