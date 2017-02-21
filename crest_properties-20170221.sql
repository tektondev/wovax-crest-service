-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2017 at 04:47 PM
-- Server version: 5.5.52-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `crest_properties`
--

-- --------------------------------------------------------

--
-- Table structure for table `crest_agents`
--

CREATE TABLE IF NOT EXISTS `crest_agents` (
  `agent_id` varchar(300) CHARACTER SET utf8 NOT NULL,
  `agent_display_name` text CHARACTER SET utf8 NOT NULL,
  `team_id` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `staff_id` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `agent_id` (`agent_id`),
  KEY `team_id` (`team_id`,`staff_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crest_feeds`
--

CREATE TABLE IF NOT EXISTS `crest_feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `brand_code` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `token` text,
  `token_expires` text,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `crest_feeds`
--

INSERT INTO `crest_feeds` (`id`, `user`, `pwd`, `brand_code`, `updated`, `token`, `token_expires`) VALUES
(1, 'WSSIR800310', 'm!yEDR5w', 'SIR', '2017-02-21 23:32:10', 'ObSSOCookie=x2in57YlX5AI7wJG7zi9v5a8I4z7jLiII1nnyVvh02o1qo3vPTNi%2Fm0%2BfcDtKfJJVNzP6Q1NUzXHdQvPnDsv%2FKH5AwPkxgHSLvOhtk%2BGvfONviFLIAy6b2SvyJG53F6Ybf8K1nw%2FokraPl%2BUJjT%2F8xvre6US1tF4NzESunOZWItPWFZqE%2Fn9VgmPf83orTHtvRlV1GbdBd%2FB3CQ%2B0LMXcXC5AsfUN5QXmLO0%2BsDfYbKG8tW8epTrApn5%2FJuXr%2FgWOVIvLQ4OOdgDpAUdgpb%2Fef3cx3zimAtwRXlfd9dIFZmvtAoBe5AQAXyLz5gvaio%2FlxuyRkkZccMJ1Yua%2FhpC', '2017-02-22T05:02:53.5427751Z');

-- --------------------------------------------------------

--
-- Table structure for table `crest_feed_properties`
--

CREATE TABLE IF NOT EXISTS `crest_feed_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `Property_ID` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `feed_id` (`feed_id`),
  KEY `property_id` (`Property_ID`),
  KEY `feed_id_2` (`feed_id`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_properties`
--

CREATE TABLE IF NOT EXISTS `crest_properties` (
  `Property_ID` varchar(300) NOT NULL,
  `MLS_ID` varchar(100) NOT NULL,
  `AdditionalMLS` varchar(300) DEFAULT NULL,
  `AddressType` mediumtext,
  `AddressLine1` mediumtext,
  `AddressLine2` mediumtext,
  `AddressLine3` mediumtext,
  `AgriculturalPropertyNumber` int(11) NOT NULL,
  `AlternateListPrice` varchar(300) DEFAULT NULL,
  `AlternateListPriceCurrency` varchar(20) DEFAULT NULL,
  `AnchorStores` varchar(300) DEFAULT NULL,
  `AnnualTax` varchar(300) DEFAULT NULL,
  `Area` mediumtext,
  `AreaUnit` mediumtext,
  `AvailableFloors` mediumtext NOT NULL,
  `AvailableSpace` mediumtext NOT NULL,
  `AverageFloorSize` varchar(300) DEFAULT NULL,
  `AverageOccupancyRate` mediumtext,
  `BayDepth` mediumtext,
  `BrandCode` mediumtext,
  `BrandName` mediumtext,
  `BuildingClass` mediumtext,
  `BuildingClassCode` mediumtext,
  `CapRatePercent` mediumtext,
  `CashFlow` mediumtext NOT NULL,
  `CeilingHeight` varchar(300) DEFAULT NULL,
  `City` mediumtext,
  `Clearance` mediumtext,
  `ClosedDate` datetime DEFAULT NULL,
  `ColumnSpacing` varchar(300) DEFAULT NULL,
  `CommonAreaFactor` mediumtext,
  `County` mediumtext,
  `CountryCode` mediumtext,
  `CountryName` mediumtext,
  `DateAvailable` datetime DEFAULT NULL,
  `defaultPropertyName` varchar(300) DEFAULT NULL,
  `DevelopmentID` mediumtext,
  `DockHeight` mediumtext,
  `DownPayment` mediumtext NOT NULL,
  `EstimatedCloseDate` datetime DEFAULT NULL,
  `ExpirationDate` datetime DEFAULT NULL,
  `FullBath` mediumtext,
  `FullyLeasedIncome` varchar(300) DEFAULT NULL,
  `GrossIncome` mediumtext NOT NULL,
  `HalfBath` mediumtext,
  `HasRailAccess` varchar(300) DEFAULT NULL,
  `IsAllowMapping` mediumtext,
  `IsCallToShow` mediumtext,
  `IsCrossDocks` varchar(300) DEFAULT NULL,
  `IsForAuction` int(11) DEFAULT NULL,
  `IsForeClosure` int(11) DEFAULT NULL,
  `IsGroundLevel` mediumtext,
  `IsHideListPrice` mediumtext,
  `IsNewConstruction` mediumtext,
  `IsPriceuponRequest` mediumtext,
  `IsShortSale` int(11) DEFAULT NULL,
  `IsShowAddressOnInternet` mediumtext,
  `IsShownOnInternet` mediumtext,
  `IsSprinkler` varchar(300) DEFAULT NULL,
  `IsSubLease` varchar(300) DEFAULT NULL,
  `LastSoldOn` mediumtext,
  `LastUpdatedBy` mediumtext,
  `LastUpdatedDate` mediumtext,
  `Latitude` varchar(300) DEFAULT NULL,
  `ListedDate` datetime DEFAULT NULL,
  `ListingContractDate` datetime DEFAULT NULL,
  `ListingFees` mediumtext,
  `ListPrice` mediumtext,
  `ListPriceCurrency` mediumtext,
  `Longitude` varchar(300) DEFAULT NULL,
  `LotArea` mediumtext,
  `LotAreaUnit` mediumtext,
  `LotDimension` mediumtext,
  `LoanPayment` mediumtext NOT NULL,
  `MaxContiguousArea` mediumtext,
  `MinDivisibleArea` mediumtext,
  `NetoperatingIncome` mediumtext NOT NULL,
  `NoOfBedrooms` mediumtext,
  `NoOfDocks` mediumtext,
  `NoOfParkingPlaces` mediumtext,
  `NumberofBallrooms` mediumtext,
  `NumberofConferenceRooms` mediumtext,
  `NumberOfLevels` mediumtext,
  `OccupancyRate` mediumtext NOT NULL,
  `OfficeId` mediumtext,
  `OfficeName` mediumtext,
  `OfficeTradeName` mediumtext,
  `ParkingRatio` mediumtext,
  `PostalCode` mediumtext,
  `Power` varchar(300) DEFAULT NULL,
  `PricePerArea` varchar(300) DEFAULT NULL,
  `PrimaryAgentId` text,
  `PrimaryAgentName` text,
  `PrimaryAgentStaffId` text,
  `PropertyLocationDescription` mediumtext,
  `PropertyListingFeatures` text,
  `PropertyDescription` mediumtext,
  `PropertyStyle` mediumtext,
  `PropertyStyleCode` mediumtext,
  `PropertySubType` varchar(300) DEFAULT NULL,
  `PropertyType` varchar(300) DEFAULT NULL,
  `PropertyUse` mediumtext,
  `QuarterBath` mediumtext,
  `Rent` text,
  `RentCurrency` text,
  `RFGListingID` mediumtext,
  `ScheduleIncome` mediumtext,
  `SchoolElementary` text,
  `SchoolMiddle` text,
  `SchoolHigh` text,
  `SourcePropertyType` mediumtext,
  `StateCode` mediumtext,
  `StateName` mediumtext,
  `Status` mediumtext,
  `SubUnits` varchar(300) DEFAULT NULL,
  `SuiteApartmentName` varchar(300) DEFAULT NULL,
  `TaxRollNo` mediumtext,
  `TaxIDNumber` mediumtext,
  `TaxYear` varchar(300) DEFAULT NULL,
  `TermDays` mediumtext NOT NULL,
  `ThreeQuarterBath` mediumtext,
  `TotalAcres` varchar(300) DEFAULT NULL,
  `TotalExpenses` mediumtext NOT NULL,
  `TotalRooms` mediumtext,
  `TotalUnits` mediumtext,
  `TurningRadius` varchar(300) DEFAULT NULL,
  `VersionNumber` mediumtext,
  `WebURL` mediumtext,
  `WithdrawnOn` mediumtext,
  `wovaxUpdated` datetime DEFAULT NULL,
  `YearBuilt` mediumtext,
  `YearRenovated` mediumtext,
  `ZoomLevel` mediumtext,
  `Zoning` mediumtext,
  PRIMARY KEY (`Property_ID`),
  KEY `MLS_ID` (`MLS_ID`),
  KEY `MLS_ID_2` (`MLS_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_agents`
--

CREATE TABLE IF NOT EXISTS `crest_property_agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Property_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `MLS_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `agent_id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `is_primary` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Property_ID` (`Property_ID`,`MLS_ID`,`agent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_features`
--

CREATE TABLE IF NOT EXISTS `crest_property_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MLS_ID` varchar(100) DEFAULT NULL,
  `Property_ID` varchar(100) DEFAULT NULL,
  `FeatureName` mediumtext,
  `FeatureGroup` mediumtext,
  `FeatureCode` mediumtext,
  PRIMARY KEY (`id`),
  KEY `MLS_ID` (`MLS_ID`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_georegions`
--

CREATE TABLE IF NOT EXISTS `crest_property_georegions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MLS_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Property_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `type_code` text CHARACTER SET utf8,
  `area_type` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `MLS_ID` (`MLS_ID`,`Property_ID`),
  KEY `MLS_ID_2` (`MLS_ID`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_images`
--

CREATE TABLE IF NOT EXISTS `crest_property_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(200) DEFAULT NULL,
  `MLS_ID` varchar(100) DEFAULT NULL,
  `Property_ID` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `MLS_ID` (`MLS_ID`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_remarks`
--

CREATE TABLE IF NOT EXISTS `crest_property_remarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MLS_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Property_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `remark_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `MLS_ID` (`MLS_ID`,`Property_ID`,`remark_name`),
  KEY `MLS_ID_2` (`MLS_ID`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_schools`
--

CREATE TABLE IF NOT EXISTS `crest_property_schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Property_ID` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `MLS_ID` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `type` text CHARACTER SET utf8,
  `name` text CHARACTER SET utf16,
  PRIMARY KEY (`id`),
  KEY `Property_ID` (`Property_ID`,`MLS_ID`),
  KEY `Property_ID_2` (`Property_ID`,`MLS_ID`),
  KEY `Property_ID_3` (`Property_ID`,`MLS_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `update_queue`
--

CREATE TABLE IF NOT EXISTS `update_queue` (
  `Property_ID` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL,
  `type` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
