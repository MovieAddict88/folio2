import React from 'react';
import './TopBar.css';

const TopBar = ({ onMenuClick }) => {
  return (
    <header className="top-bar">
      <button className="menu-button" onClick={onMenuClick}>
        ☰
      </button>
      <div className="portfolio-title">
        My Portfolio
      </div>
      <div className="theme-switcher">
        {/* Placeholder for theme switcher */}
        <span>☀️</span>
      </div>
    </header>
  );
};

export default TopBar;