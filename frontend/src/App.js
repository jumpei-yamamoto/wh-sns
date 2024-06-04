import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import Login from "./components/Login";
import Signup from "./components/Signup";
import HomeFeed from "./components/HomeFeed";
import ProfileSetup from "./components/ProfileSetup";
import CreateThread from "./components/CreateThread";
import ThreadList from "./components/ThreadList";
import ThreadPage from "./components/ThreadPage";
import Message from "./components/Message";
import Notifications from "./components/Notifications";
import Search from "./components/Search";
import MessagesList from "./components/MessagesList";

const App = () => {
  return (
    <Router basename="/sns/backend/">
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/login" element={<Login />} />
        <Route path="/signup" element={<Signup />} />
        <Route path="/home" element={<HomeFeed />} />
        <Route path="/profile-setup" element={<ProfileSetup />} />
        <Route path="/threads" element={<ThreadList />} />
        <Route path="/create-thread" element={<CreateThread />} />
        <Route path="/thread/:threadId" element={<ThreadPage />} />
        <Route path="/message/:receiverId" element={<Message />} />
        <Route path="/notifications" element={<Notifications />} />
        <Route path="/search" element={<Search />} />
        <Route path="/messages" element={<MessagesList />} />
      </Routes>
    </Router>
  );
};

export default App;
