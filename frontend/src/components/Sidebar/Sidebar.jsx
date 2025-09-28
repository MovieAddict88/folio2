import React from 'react';
import './Sidebar.css';

const Sidebar = () => {
  return (
    <aside className="sidebar">
      <div className="profile">
        {/* Placeholder for Logo / Profile Pic */}
        <div className="profile-pic"></div>
        <h3>My Portfolio</h3>
      </div>
      <nav>
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#about">About Me</a></li>
          <li><a href="#education">Education</a></li>
          <li><a href="#experience">Experience</a></li>
          <li><a href="#skills">Skills</a></li>
          <li><a href="#projects">Projects</a></li>
          <li><a href="#testimonials">Testimonials</a></li>
          <li><a href="#downloads">Downloads</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
      <div className="sidebar-footer">
        <div className="search-filter">
          {/* This input is a placeholder for now. Functionality will be in the Projects/Skills sections */}
          <input type="text" placeholder="🔍 Search..." disabled />
        </div>
        <div className="admin-login">
          <a href="/admin/login">⚙️ Admin Login</a>
        </div>
      </div>
    </aside>
  );
};

export default Sidebar;