import React from 'react';
import './MainPanel.css';
import Home from '../sections/Home/Home';
import About from '../sections/About/About';
import Education from '../sections/Education/Education';
import Experience from '../sections/Experience/Experience';
import Skills from '../sections/Skills/Skills';
import Projects from '../sections/Projects/Projects';
import Testimonials from '../sections/Testimonials/Testimonials';
import Downloads from '../sections/Downloads/Downloads';
import Contact from '../sections/Contact/Contact';

const MainPanel = () => {
  return (
    <main className="main-panel">
      <section id="home"><Home /></section>
      <section id="about"><About /></section>
      <section id="education"><Education /></section>
      <section id="experience"><Experience /></section>
      <section id="skills"><Skills /></section>
      <section id="projects"><Projects /></section>
      <section id="testimonials"><Testimonials /></section>
      <section id="downloads"><Downloads /></section>
      <section id="contact"><Contact /></section>
    </main>
  );
};

export default MainPanel;