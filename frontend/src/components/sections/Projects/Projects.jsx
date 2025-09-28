import React, { useState, useEffect } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Zoom } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/zoom';
import './Projects.css';

const Projects = () => {
  const [projectsData, setProjectsData] = useState([]);
  const [filteredProjects, setFilteredProjects] = useState([]);
  const [allTags, setAllTags] = useState([]);
  const [selectedTag, setSelectedTag] = useState('All');
  const [searchTerm, setSearchTerm] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/projects.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          throw new Error(data.error);
        }
        const parsedData = data.map(p => ({
          ...p,
          external_links: p.external_links ? JSON.parse(p.external_links) : [],
          category_tags: p.category_tags ? JSON.parse(p.category_tags) : []
        }));
        setProjectsData(parsedData);
        setFilteredProjects(parsedData);

        const tags = new Set();
        parsedData.forEach(p => {
          if(p.category_tags) p.category_tags.forEach(tag => tags.add(tag));
        });
        setAllTags(['All', ...Array.from(tags)]);
        setLoading(false);
      })
      .catch(error => {
        setError(error.message);
        setLoading(false);
      });
  }, []);

  useEffect(() => {
    let result = projectsData;
    if (selectedTag && selectedTag !== 'All') {
      result = result.filter(p => p.category_tags.includes(selectedTag));
    }
    if (searchTerm) {
      result = result.filter(p =>
        p.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        p.description.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }
    setFilteredProjects(result);
  }, [selectedTag, searchTerm, projectsData]);

  if (loading) return <div className="projects-section"><h2>Projects</h2><p>Loading...</p></div>;
  if (error) return <div className="projects-section"><h2>Projects</h2><p>Error: {error}</p></div>;

  return (
    <div className="projects-section">
      <h2>Projects</h2>
      <div className="filter-controls">
        <input
          type="text"
          placeholder="Search projects..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          className="search-input"
        />
        <div className="tag-filters">
          {allTags.map(tag => (
            <button
              key={tag}
              className={`tag-button ${selectedTag === tag ? 'active' : ''}`}
              onClick={() => setSelectedTag(tag)}
            >
              {tag}
            </button>
          ))}
        </div>
      </div>
      <Swiper
        modules={[Navigation, Pagination, Zoom]}
        spaceBetween={50}
        slidesPerView={1}
        navigation
        pagination={{ clickable: true }}
        zoom={true}
      >
        {filteredProjects.map((project, index) => (
          <SwiperSlide key={index}>
            <div className="project-item">
              <div className="swiper-zoom-container">
                <img src={project.media_url} alt={project.title} />
              </div>
              <h3>{project.title}</h3>
              <p>{project.description}</p>
              <div className="external-links">
                {project.external_links && project.external_links.map(link => (
                  <a key={link.url} href={link.url} target="_blank" rel="noopener noreferrer">{link.label}</a>
                ))}
              </div>
              <div className="category-tags">
                {project.category_tags && project.category_tags.map(tag => (
                  <span key={tag} className="tag">{tag}</span>
                ))}
              </div>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  );
};

export default Projects;