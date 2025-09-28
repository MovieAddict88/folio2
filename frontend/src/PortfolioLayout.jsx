import React, { useState, useEffect } from 'react';
import Sidebar from './components/Sidebar/Sidebar';
import MainPanel from './components/MainPanel/MainPanel';
import TopBar from './components/TopBar/TopBar';
import Drawer from './components/Drawer/Drawer';

const PortfolioLayout = () => {
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);
  const [isDrawerOpen, setDrawerOpen] = useState(false);

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768);
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const toggleDrawer = () => setDrawerOpen(!isDrawerOpen);

  return (
    <div className="app">
      {isMobile ? (
        <>
          <TopBar onMenuClick={toggleDrawer} />
          <Drawer isOpen={isDrawerOpen} onClose={toggleDrawer} />
        </>
      ) : (
        <Sidebar />
      )}
      <MainPanel />
    </div>
  );
};

export default PortfolioLayout;