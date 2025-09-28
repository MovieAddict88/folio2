import React from 'react';
import './Drawer.css';

const Drawer = ({ isOpen, onClose }) => {
  return (
    <>
      <div className={`drawer-overlay ${isOpen ? 'open' : ''}`} onClick={onClose}></div>
      <div className={`drawer ${isOpen ? 'open' : ''}`}>
        <button className="close-button" onClick={onClose}>&times;</button>
        <nav>
          <ul>
            <li><a href="#home" onClick={onClose}>Home</a></li>
            <li><a href="#about" onClick={onClose}>About Me</a></li>
            <li><a href="#education" onClick={onClose}>Education</a></li>
            <li><a href="#experience" onClick={onClose}>Experience</a></li>
            <li><a href="#skills" onClick={onClose}>Skills</a></li>
            <li><a href="#projects" onClick={onClose}>Projects</a></li>
            <li><a href="#testimonials" onClick={onClose}>Testimonials</a></li>
            <li><a href="#downloads" onClick={onClose}>Downloads</a></li>
            <li><a href="#contact" onClick={onClose}>Contact</a></li>
          </ul>
        </nav>
        <div className="drawer-footer">
          <div className="search-filter">
            <input type="text" placeholder="🔍 Search / Filter" />
          </div>
          <div className="admin-login">
            <a href="/admin/login">⚙️ Admin Login</a>
          </div>
        </div>
      </div>
    </>
  );
};

export default Drawer;