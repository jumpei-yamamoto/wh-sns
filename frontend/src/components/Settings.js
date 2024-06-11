import React from "react";
import { useNavigate } from "react-router-dom";
import api from "../api";

const Settings = () => {
  const navigate = useNavigate();

  const handleDeleteAccount = async () => {
    if (
      window.confirm(
        "本当にアカウントを削除しても良いですか? この操作は取り消し出来ません。"
      )
    ) {
      try {
        const response = await api.post("/deleteAccount.php");
        if (response.data.success) {
          alert("Account deleted successfully.");
          navigate("/signup");
        } else {
          alert("Failed to delete account: " + response.data.message);
        }
      } catch (error) {
        alert("An error occurred: " + error.message);
      }
    }
  };

  return (
    <div className="min-h-screen flex flex-col items-center bg-gray-100">
      <div className="bg-white p-8 rounded shadow-md w-full max-w-2xl mt-8">
        <h1 className="text-2xl font-bold mb-4">Settings</h1>
        <button
          onClick={() => navigate("/home")}
          className="mb-4 bg-blue-500 text-white p-2 mr-5 rounded"
        >
          Back to Home
        </button>
        <button
          onClick={handleDeleteAccount}
          className="bg-red-500 text-white p-2 rounded"
        >
          Delete Account
        </button>
      </div>
    </div>
  );
};

export default Settings;
